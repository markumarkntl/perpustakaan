<?php
/** @var array $peminjamanList */
/** @var array $anggotaList */
/** @var array $bukuList */
?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('message')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Transaksi Peminjaman</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPeminjaman" onclick="bukaModalTambahPeminjaman()">
                    <i class="bi bi-plus-lg"></i> Tambah Peminjaman
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;"></th>
                                <th style="width: 50px;">No</th>
                                <th>Anggota</th>
                                <th>Kelas</th>
                                <th class="text-center">Tanggal Pinjam</th>
                                <th class="text-center">Jatuh Tempo</th>
                                <th>Petugas</th>
                                <th class="text-center">Jumlah Buku</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($peminjamanList)) : ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada transaksi peminjaman.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($peminjamanList as $pinjam) : ?>
                                    <?php $collapseId = 'detail-pinjam-' . $pinjam['id_peminjaman']; ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                                                <i class="bi bi-chevron-down"></i>
                                            </button>
                                        </td>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($pinjam['nama_anggota']) ?></td>
                                        <td><?= esc($pinjam['kelas']) ?></td>
                                        <td class="text-center"><?= esc(date('d-m-Y', strtotime($pinjam['tanggal_pinjam']))) ?></td>
                                        <td class="text-center"><?= esc(date('d-m-Y', strtotime($pinjam['tanggal_jatuh_tempo']))) ?></td>
                                        <td><?= esc($pinjam['nama_petugas']) ?></td>
                                        <td class="text-center"><?= count($pinjam['items']) ?></td>
                                        <td class="text-center">
                                            <?php if ($pinjam['status'] === 'selesai') : ?>
                                                <span class="badge bg-success">Selesai</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="<?= $collapseId ?>">
                                        <td></td>
                                        <td colspan="8" class="p-0">
                                            <div class="p-3 bg-light">
                                                <table class="table table-sm table-bordered mb-0 bg-white">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Judul Buku</th>
                                                            <th class="text-center">Jumlah</th>
                                                            <th class="text-center">Tanggal Kembali</th>
                                                            <th class="text-center">Denda</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($pinjam['items'])) : ?>
                                                            <tr>
                                                                <td colspan="6" class="text-center text-muted">Tidak ada item buku.</td>
                                                            </tr>
                                                        <?php else : ?>
                                                            <?php foreach ($pinjam['items'] as $item) : ?>
                                                                <tr>
                                                                    <td><?= esc($item['kode_buku']) ?></td>
                                                                    <td><?= esc($item['judul']) ?></td>
                                                                    <td class="text-center"><?= (int) $item['jumlah'] ?></td>
                                                                    <td class="text-center">
                                                                        <?= empty($item['tanggal_kembali'])
                                                                            ? '-'
                                                                            : esc(date('d-m-Y', strtotime($item['tanggal_kembali']))) ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= $item['denda'] > 0
                                                                            ? 'Rp ' . number_format((float) $item['denda'], 0, ',', '.')
                                                                            : '-' ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php if (! empty($item['tanggal_kembali'])) : ?>
                                                                            <span class="badge bg-success">Sudah Kembali</span>
                                                                        <?php else : ?>
                                                                            <span class="badge bg-warning text-dark">Belum Kembali</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                <?php if ($pinjam['status'] !== 'selesai') : ?>
                                                    <p class="text-muted small mt-2 mb-0">
                                                        Untuk memproses pengembalian, buka menu
                                                        <a href="<?= base_url('pengembalian') ?>">Pengembalian Buku</a>.
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="modalTambahPeminjaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTambahPeminjaman" method="post" action="<?= base_url('peminjaman/store') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="id_anggota" class="form-label">Anggota</label>
                            <select class="form-select" id="id_anggota" name="id_anggota" required>
                                <option value="">-- Pilih Anggota --</option>
                                <?php foreach ($anggotaList as $anggota) : ?>
                                    <option value="<?= (int) $anggota['id_anggota'] ?>">
                                        <?= esc($anggota['nama']) ?> (<?= esc($anggota['kelas']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_jatuh_tempo" class="form-label">Jatuh Tempo</label>
                            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Daftar Buku Dipinjam</label>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahBarisBuku">
                            <i class="bi bi-plus-lg"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="containerBarisBuku"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template baris buku (dipakai JS untuk cloning, tidak ditampilkan langsung) -->
<template id="templateBarisBuku">
    <div class="row g-2 align-items-end mb-2 baris-buku">
        <div class="col-md-7">
            <select class="form-select" name="id_buku[]" required>
                <option value="">-- Pilih Buku --</option>
                <?php foreach ($bukuList as $buku) : ?>
                    <option value="<?= (int) $buku['id_buku'] ?>" data-stok="<?= (int) $buku['stok'] ?>">
                        <?= esc($buku['judul']) ?> (<?= esc($buku['kode_buku']) ?>) - stok <?= (int) $buku['stok'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="jumlah[]" min="1" value="1" placeholder="Jumlah" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger w-100 btn-hapus-baris">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</template>

<script>
    const containerBarisBuku = document.getElementById('containerBarisBuku');
    const templateBarisBuku  = document.getElementById('templateBarisBuku');

    function tambahBarisBuku() {
        const clone = templateBarisBuku.content.cloneNode(true);
        clone.querySelector('.btn-hapus-baris').addEventListener('click', function () {
            hapusBarisBuku(this);
        });
        clone.querySelector('select[name="id_buku[]"]').addEventListener('change', perbaruiOpsiBukuTerpilih);
        containerBarisBuku.appendChild(clone);
        perbaruiOpsiBukuTerpilih();
    }

    function hapusBarisBuku(tombol) {
        const semuaBaris = containerBarisBuku.querySelectorAll('.baris-buku');
        if (semuaBaris.length <= 1) {
            Swal.fire('Tidak bisa dihapus', 'Minimal 1 baris buku harus diisi.', 'warning');
            return;
        }
        tombol.closest('.baris-buku').remove();
        perbaruiOpsiBukuTerpilih();
    }

    // Cegah 1 judul buku yang sama kepilih di lebih dari 1 baris sekaligus
    // (supaya tidak salah kira "nambah baris" padahal buku yang dipilih sama).
    function perbaruiOpsiBukuTerpilih() {
        const semuaSelect = containerBarisBuku.querySelectorAll('select[name="id_buku[]"]');
        const dipilih = Array.from(semuaSelect).map(sel => sel.value).filter(v => v !== '');

        semuaSelect.forEach(sel => {
            Array.from(sel.options).forEach(opt => {
                if (opt.value === '') return;
                const dipakaiDiBarisLain = dipilih.includes(opt.value) && sel.value !== opt.value;
                opt.disabled = dipakaiDiBarisLain;
            });
        });
    }

    document.getElementById('btnTambahBarisBuku').addEventListener('click', tambahBarisBuku);

    function bukaModalTambahPeminjaman() {
        document.getElementById('formTambahPeminjaman').reset();
        containerBarisBuku.innerHTML = '';
        tambahBarisBuku();
    }

    // Tampilkan ringkasan buku yang akan dipinjam SEBELUM submit,
    // supaya jelas terlihat kalau memang lebih dari 1 buku terkirim.
    document.getElementById('formTambahPeminjaman').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const baris = containerBarisBuku.querySelectorAll('.baris-buku');
        let ringkasan = '';
        let totalBaris = 0;

        baris.forEach(b => {
            const select = b.querySelector('select[name="id_buku[]"]');
            const jumlah = b.querySelector('input[name="jumlah[]"]').value;
            const judul  = select.options[select.selectedIndex]?.text ?? '(belum dipilih)';
            if (select.value !== '') {
                totalBaris++;
                ringkasan += `<li>${judul} — jumlah: ${jumlah}</li>`;
            }
        });

        Swal.fire({
            title: `Konfirmasi ${totalBaris} Buku`,
            html: `<ul style="text-align:left">${ringkasan}</ul>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>