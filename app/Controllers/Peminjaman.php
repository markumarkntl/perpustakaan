<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\DetailPeminjamanModel;
use App\Models\BukuModel;
use App\Models\AnggotaModel;

class Peminjaman extends BaseController
{
    protected PeminjamanModel $peminjamanModel;
    protected DetailPeminjamanModel $detailModel;
    protected BukuModel $bukuModel;
    protected AnggotaModel $anggotaModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailModel     = new DetailPeminjamanModel();
        $this->bukuModel       = new BukuModel();
        $this->anggotaModel    = new AnggotaModel();
    }

    /**
     * Daftar semua transaksi peminjaman (aktif & selesai) beserta
     * item-item bukunya, plus data pendukung untuk form peminjaman baru.
     */
    public function index()
    {
        $peminjamanList = $this->peminjamanModel->getAllWithRelasi();

        // Lampirkan daftar item buku pada tiap transaksi.
        foreach ($peminjamanList as &$pinjam) {
            $pinjam['items'] = $this->detailModel->getByPeminjaman((int) $pinjam['id_peminjaman']);
        }
        unset($pinjam);

        $data = [
            'nama_petugas'   => session()->get('nama_petugas'),
            'peminjamanList' => $peminjamanList,
            'anggotaList'    => $this->anggotaModel->orderBy('nama', 'ASC')->findAll(),
            'bukuList'       => $this->bukuModel->where('stok >', 0)->orderBy('judul', 'ASC')->findAll(),

            'extraStyles'  => ['assets/extensions/sweetalert2/sweetalert2.min.css'],
            'extraScripts' => ['assets/extensions/sweetalert2/sweetalert2.min.js'],
        ];

        $data['content']    = view('peminjaman/index', $data);
        $data['title']      = 'Transaksi Peminjaman';
        $data['activeMenu'] = 'transaksi';
        $data['activeSub']  = 'peminjaman';

        return view('layouts/main', $data);
    }

    /**
     * Simpan transaksi peminjaman baru beserta item-item bukunya.
     * Stok tiap buku yang dipinjam otomatis dikurangi sesuai jumlah.
     *
     * Format input yang diharapkan dari form:
     *   id_anggota, tanggal_pinjam, tanggal_jatuh_tempo,
     *   id_buku[]  -> array id buku yang dipinjam
     *   jumlah[]   -> array jumlah tiap buku (index selaras dengan id_buku[])
     */
    public function store()
    {
        $idPetugas = session()->get('id_petugas');

        if (! $idPetugas) {
            return redirect()->to('/')->with('error_login', 'Sesi login habis, silakan login kembali.');
        }

        $payload = [
            'id_anggota'          => (int) $this->request->getPost('id_anggota'),
            'id_petugas'          => (int) $idPetugas,
            'tanggal_pinjam'      => trim((string) $this->request->getPost('tanggal_pinjam')),
            'tanggal_jatuh_tempo' => trim((string) $this->request->getPost('tanggal_jatuh_tempo')),
            'status'              => 'dipinjam',
        ];

        if (! $this->peminjamanModel->validate($payload)) {
            return redirect()->to('/peminjaman')
                ->withInput()
                ->with('errors', $this->peminjamanModel->errors());
        }

        $idBukuList = (array) $this->request->getPost('id_buku');
        $jumlahList = (array) $this->request->getPost('jumlah');

        // Rapikan input: buang baris kosong, gabungkan buku yang sama.
        $items = $this->rapikanItemBuku($idBukuList, $jumlahList);

        if (empty($items)) {
            return redirect()->to('/peminjaman')
                ->withInput()
                ->with('error', 'Minimal pilih 1 buku untuk dipinjam.');
        }

        // Cek stok tiap buku sebelum transaksi dijalankan.
        $errorStok = $this->cekKetersediaanStok($items);

        if ($errorStok !== null) {
            return redirect()->to('/peminjaman')
                ->withInput()
                ->with('error', $errorStok);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $idPeminjaman = $this->peminjamanModel->insert($payload, true);

        foreach ($items as $item) {
            $this->detailModel->insert([
                'id_peminjaman' => $idPeminjaman,
                'id_buku'       => $item['id_buku'],
                'jumlah'        => $item['jumlah'],
            ]);

            // Kurangi stok buku sesuai jumlah yang dipinjam.
            $this->bukuModel->where('id_buku', $item['id_buku'])
                ->set('stok', 'stok - ' . $item['jumlah'], false)
                ->update();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/peminjaman')
                ->withInput()
                ->with('error', 'Transaksi peminjaman gagal disimpan, silakan coba lagi.');
        }

        return redirect()->to('/peminjaman')
            ->with('message', 'Transaksi peminjaman berhasil disimpan.');
    }

    /**
     * Bersihkan pasangan id_buku[] & jumlah[] dari input:
     * buang baris yang id_buku/jumlahnya tidak valid, dan gabungkan
     * jumlah jika ada buku yang sama dipilih lebih dari sekali.
     */
    private function rapikanItemBuku(array $idBukuList, array $jumlahList): array
    {
        $items = [];

        foreach ($idBukuList as $index => $idBuku) {
            $idBuku = (int) $idBuku;
            $jumlah = (int) ($jumlahList[$index] ?? 0);

            if ($idBuku <= 0 || $jumlah <= 0) {
                continue;
            }

            if (isset($items[$idBuku])) {
                $items[$idBuku]['jumlah'] += $jumlah;
            } else {
                $items[$idBuku] = ['id_buku' => $idBuku, 'jumlah' => $jumlah];
            }
        }

        return array_values($items);
    }

    /**
     * Cek apakah stok tiap buku pada daftar item mencukupi.
     * Mengembalikan pesan error (string) jika ada yang tidak cukup,
     * atau null jika semua aman.
     */
    private function cekKetersediaanStok(array $items): ?string
    {
        foreach ($items as $item) {
            $buku = $this->bukuModel->find($item['id_buku']);

            if (! $buku) {
                return 'Salah satu buku yang dipilih tidak ditemukan.';
            }

            if ($buku['stok'] < $item['jumlah']) {
                return "Stok buku \"{$buku['judul']}\" tidak mencukupi (sisa {$buku['stok']}).";
            }
        }

        return null;
    }

        /**
     * Proses pengembalian SATU item buku dari sebuah transaksi peminjaman.
     * - Menghitung denda otomatis jika telat dari tanggal_jatuh_tempo.
     * - Mengembalikan stok buku sesuai jumlah yang dipinjam.
     * - Jika seluruh item pada transaksi sudah kembali, status
     *   peminjaman otomatis diubah menjadi 'selesai'.
     *
     * Input opsional: tanggal_kembali (default: hari ini).
     */
    public function kembalikan($idDetail = null)
    {
        $detail = $this->detailModel->getOneWithRelasi((int) $idDetail);

        if (! $detail) {
            return redirect()->to('/peminjaman')
                ->with('error', 'Data peminjaman buku tidak ditemukan.');
        }

        if (! empty($detail['tanggal_kembali'])) {
            return redirect()->to('/peminjaman')
                ->with('error', "Buku \"{$detail['judul']}\" sudah tercatat dikembalikan sebelumnya.");
        }

        $tanggalKembali = trim((string) $this->request->getPost('tanggal_kembali'));
        $tanggalKembali = $tanggalKembali !== '' ? $tanggalKembali : date('Y-m-d');

        $denda = $this->detailModel->hitungDenda($detail['tanggal_jatuh_tempo'], $tanggalKembali);

        $db = \Config\Database::connect();
        $db->transStart();

        $this->detailModel->update($detail['id_detail'], [
            'tanggal_kembali' => $tanggalKembali,
            'denda'           => $denda,
        ]);

        // Kembalikan stok buku sesuai jumlah yang dipinjam pada item ini.
        $this->bukuModel->where('id_buku', $detail['id_buku'])
            ->set('stok', 'stok + ' . (int) $detail['jumlah'], false)
            ->update();

        // Jika seluruh item pada transaksi ini sudah kembali, tutup transaksinya.
        if ($this->peminjamanModel->semuaDetailSudahKembali((int) $detail['id_peminjaman'])) {
            $this->peminjamanModel->update($detail['id_peminjaman'], ['status' => 'selesai']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/peminjaman')
                ->with('error', 'Proses pengembalian gagal, silakan coba lagi.');
        }

        $pesan = $denda > 0
            ? "Buku \"{$detail['judul']}\" berhasil dikembalikan. Denda keterlambatan: Rp " . number_format($denda, 0, ',', '.') . '.'
            : "Buku \"{$detail['judul']}\" berhasil dikembalikan tanpa denda.";

        return redirect()->to('/peminjaman')->with('message', $pesan);
    }

    /**
     * Proses pengembalian SEMUA item buku yang belum kembali
     * dalam satu transaksi peminjaman sekaligus (dipakai jika
     * anggota mengembalikan seluruh buku pinjamannya bersamaan).
     *
     * Input opsional: tanggal_kembali (default: hari ini).
     */
    public function kembalikanSemua($idPeminjaman = null)
    {
        $peminjaman = $this->peminjamanModel->find((int) $idPeminjaman);

        if (! $peminjaman) {
            return redirect()->to('/peminjaman')
                ->with('error', 'Transaksi peminjaman tidak ditemukan.');
        }

        $items = array_filter(
            $this->detailModel->getByPeminjaman((int) $idPeminjaman),
            fn ($item) => empty($item['tanggal_kembali'])
        );

        if (empty($items)) {
            return redirect()->to('/peminjaman')
                ->with('error', 'Semua buku pada transaksi ini sudah dikembalikan.');
        }

        $tanggalKembali = trim((string) $this->request->getPost('tanggal_kembali'));
        $tanggalKembali = $tanggalKembali !== '' ? $tanggalKembali : date('Y-m-d');

        $db = \Config\Database::connect();
        $db->transStart();

        $totalDenda = 0.0;

        foreach ($items as $item) {
            $denda = $this->detailModel->hitungDenda($peminjaman['tanggal_jatuh_tempo'], $tanggalKembali);
            $totalDenda += $denda;

            $this->detailModel->update($item['id_detail'], [
                'tanggal_kembali' => $tanggalKembali,
                'denda'           => $denda,
            ]);

            $this->bukuModel->where('id_buku', $item['id_buku'])
                ->set('stok', 'stok + ' . (int) $item['jumlah'], false)
                ->update();
        }

        $this->peminjamanModel->update($idPeminjaman, ['status' => 'selesai']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/peminjaman')
                ->with('error', 'Proses pengembalian gagal, silakan coba lagi.');
        }

        $pesan = $totalDenda > 0
            ? 'Seluruh buku berhasil dikembalikan. Total denda: Rp ' . number_format($totalDenda, 0, ',', '.') . '.'
            : 'Seluruh buku berhasil dikembalikan tanpa denda.';

        return redirect()->to('/peminjaman')->with('message', $pesan);
    }
}

