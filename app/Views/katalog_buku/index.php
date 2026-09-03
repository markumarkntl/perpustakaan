<?php
/** @var array $bukuList */
/** @var array $kategoriList */

$extraStyles  = ['assets/extensions/sweetalert2/sweetalert2.min.css'];
$extraScripts = ['assets/extensions/sweetalert2/sweetalert2.min.js'];
?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('message')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <h5 class="mb-0">Daftar Buku</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalBuku" onclick="bukaModalTambah()">
                    <i class="bi bi-plus-lg"></i> Tambah Buku
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Stok</th>
                                <th>Kategori</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bukuList)) : ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data buku.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bukuList as $buku) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($buku['kode_buku']) ?></td>
                                        <td><?= esc($buku['judul']) ?></td>
                                        <td><?= esc($buku['penulis']) ?></td>
                                        <td><?= esc($buku['penerbit'] ?? '-') ?></td>
                                        <td class="text-center"><?= esc((string) ($buku['tahun_terbit'] ?? '-')) ?></td>
                                        <td class="text-center"><?= (int) $buku['stok'] ?></td>
                                        <td><?= esc($buku['nama_kategori']) ?></td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalBuku"
                                                onclick='bukaModalEdit(<?= json_encode($buku) ?>)'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="hapusBuku(<?= (int) $buku['id_buku'] ?>, '<?= esc($buku['judul'], 'js') ?>')">
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

<!-- Modal Tambah / Edit Buku -->
<div class="modal fade" id="modalBuku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formBuku" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBukuTitle">Tambah Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="kode_buku" class="form-label">Kode Buku</label>
                            <input type="text" class="form-control" id="kode_buku" name="kode_buku" required maxlength="20">
                        </div>
                        <div class="col-md-8">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required maxlength="150">
                        </div>
                        <div class="col-md-6">
                            <label for="penulis" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="penerbit" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" maxlength="4" min="1900" max="2100">
                        </div>
                        <div class="col-md-4">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="id_kategori" name="id_kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategoriList as $kategori) : ?>
                                    <option value="<?= (int) $kategori['id_kategori'] ?>"><?= esc($kategori['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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

<form id="formHapusBuku" method="post" action="" class="d-none">
    <?= csrf_field() ?>
</form>

<script>
    const baseUrlKatalogBuku = "<?= base_url('katalog-buku') ?>";

    function bukaModalTambah() {
        document.getElementById('modalBukuTitle').innerText = 'Tambah Buku';
        document.getElementById('formBuku').action = baseUrlKatalogBuku + '/store';
        document.getElementById('formBuku').reset();
    }

    function bukaModalEdit(buku) {
        document.getElementById('modalBukuTitle').innerText = 'Edit Buku';
        document.getElementById('formBuku').action = baseUrlKatalogBuku + '/update/' + buku.id_buku;
        document.getElementById('kode_buku').value    = buku.kode_buku;
        document.getElementById('judul').value        = buku.judul;
        document.getElementById('penulis').value      = buku.penulis;
        document.getElementById('penerbit').value     = buku.penerbit ?? '';
        document.getElementById('tahun_terbit').value = buku.tahun_terbit ?? '';
        document.getElementById('stok').value         = buku.stok;
        document.getElementById('id_kategori').value  = buku.id_kategori;
    }

    function hapusBuku(id, judul) {
        Swal.fire({
            title: 'Hapus buku?',
            text: 'Buku "' + judul + '" akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapusBuku');
                form.action = baseUrlKatalogBuku + '/delete/' + id;
                form.submit();
            }
        });
    }
</script>