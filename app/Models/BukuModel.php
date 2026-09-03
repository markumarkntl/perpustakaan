<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'id_buku';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'kode_buku',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        'id_kategori',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'kode_buku'    => 'required|max_length[20]|is_unique[buku.kode_buku,id_buku,{id_buku}]',
        'judul'        => 'required|max_length[150]',
        'penulis'      => 'required|max_length[100]',
        'penerbit'     => 'permit_empty|max_length[100]',
        'tahun_terbit' => 'permit_empty|numeric|exact_length[4]',
        'stok'         => 'required|is_natural',
        'id_kategori'  => 'required|is_natural_no_zero|is_not_unique[kategori_buku.id_kategori]',
    ];
    protected $validationMessages = [
        'kode_buku' => [
            'required'  => 'Kode buku wajib diisi.',
            'is_unique' => 'Kode buku sudah dipakai, gunakan kode lain.',
        ],
        'judul'   => ['required' => 'Judul buku wajib diisi.'],
        'penulis' => ['required' => 'Nama penulis wajib diisi.'],
        'tahun_terbit' => [
            'numeric'      => 'Tahun terbit harus berupa angka.',
            'exact_length' => 'Tahun terbit harus 4 digit, contoh: 2024.',
        ],
        'stok' => [
            'required'    => 'Stok wajib diisi.',
            'is_natural'  => 'Stok harus berupa angka dan tidak boleh negatif.',
        ],
        'id_kategori' => [
            'required'       => 'Kategori wajib dipilih.',
            'is_not_unique'  => 'Kategori yang dipilih tidak valid.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Ambil semua buku beserta nama kategorinya (join),
     * dipakai untuk tabel daftar buku.
     */
    public function getAllWithKategori(): array
    {
        return $this->select('buku.*, kategori_buku.nama_kategori')
            ->join('kategori_buku', 'kategori_buku.id_kategori = buku.id_kategori')
            ->orderBy('buku.judul', 'ASC')
            ->findAll();
    }

    /**
     * Hitung berapa banyak baris detail_peminjaman yang masih
     * mereferensikan buku ini. Dipakai sebelum hapus, karena
     * FK detail_peminjaman -> buku bersifat ON DELETE RESTRICT.
     */
    public function countDetailPeminjamanByBuku(int $idBuku): int
    {
        return $this->db->table('detail_peminjaman')
            ->where('id_buku', $idBuku)
            ->countAllResults();
    }
}