<?php
// Slot CSS & JS tambahan yang dibaca oleh layouts/partials/header.php & scripts.php
$extraStyles  = ['assets/extensions/sweetalert2/sweetalert2.min.css'];
$extraScripts = ['assets/extensions/sweetalert2/sweetalert2.min.js'];
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
                <h5 class="mb-0">Daftar Kategori Buku</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalKategori" onclick="bukaModalTambah()">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama Kategori</th>
                                <th style="width: 160px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($kategoriList)) : ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada data kategori.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($kategoriList as $kategori) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($kategori['nama_kategori']) ?></td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKategori"
                                                onclick="bukaModalEdit(<?= (int) $kategori['id_kategori'] ?>, '<?= esc($kategori['nama_kategori'], 'js') ?>')">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="hapusKategori(<?= (int) $kategori['id_kategori'] ?>, '<?= esc($kategori['nama_kategori'], 'js') ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
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

<!-- Modal Tambah / Edit Kategori -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKategori" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKategoriTitle">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required maxlength="100" placeholder="Contoh: Novel, Teknologi, dst.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form tersembunyi khusus untuk proses hapus (submit via JS setelah konfirmasi SweetAlert2) -->
<form id="formHapusKategori" method="post" action="" class="d-none">
    <?= csrf_field() ?>
</form>

<script>
    const baseUrlKategoriBuku = "<?= base_url('kategori-buku') ?>";

    function bukaModalTambah() {
        document.getElementById('modalKategoriTitle').innerText = 'Tambah Kategori';
        document.getElementById('formKategori').action = baseUrlKategoriBuku + '/store';
        document.getElementById('nama_kategori').value = '';
    }

    function bukaModalEdit(id, namaKategori) {
        document.getElementById('modalKategoriTitle').innerText = 'Edit Kategori';
        document.getElementById('formKategori').action = baseUrlKategoriBuku + '/update/' + id;
        document.getElementById('nama_kategori').value = namaKategori;
    }

    function hapusKategori(id, namaKategori) {
        Swal.fire({
            title: 'Hapus kategori?',
            text: 'Kategori "' + namaKategori + '" akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapusKategori');
                form.action = baseUrlKategoriBuku + '/delete/' + id;
                form.submit();
            }
        });
    }
</script>