<?php
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
                <h5 class="mb-0">Daftar Petugas</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPetugas" onclick="bukaModalTambah()">
                    <i class="bi bi-plus-lg"></i> Tambah Petugas
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama Petugas</th>
                                <th>Username</th>
                                <th style="width: 160px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($petugasList)) : ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data petugas.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($petugasList as $petugas) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($petugas['nama_petugas']) ?></td>
                                        <td><?= esc($petugas['username']) ?></td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalPetugas"
                                                onclick="bukaModalEdit(<?= (int) $petugas['id_petugas'] ?>, '<?= esc($petugas['nama_petugas'], 'js') ?>', '<?= esc($petugas['username'], 'js') ?>')">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="hapusPetugas(<?= (int) $petugas['id_petugas'] ?>, '<?= esc($petugas['nama_petugas'], 'js') ?>')">
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

<!-- Modal Tambah / Edit Petugas -->
<div class="modal fade" id="modalPetugas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPetugas" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPetugasTitle">Tambah Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_petugas" class="form-label">Nama Petugas</label>
                        <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required maxlength="120">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" maxlength="255" placeholder="Isi password">
                        <div class="form-text" id="passwordHint">Minimal isi saat menambah petugas baru.</div>
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

<!-- Form tersembunyi khusus untuk proses hapus -->
<form id="formHapusPetugas" method="post" action="" class="d-none">
    <?= csrf_field() ?>
</form>

<script>
    const baseUrlPetugas = "<?= base_url('petugas') ?>";

    function bukaModalTambah() {
        document.getElementById('modalPetugasTitle').innerText = 'Tambah Petugas';
        document.getElementById('formPetugas').action = baseUrlPetugas + '/store';
        document.getElementById('nama_petugas').value = '';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password').required = true;
        document.getElementById('passwordHint').innerText = 'Minimal isi saat menambah petugas baru.';
    }

    function bukaModalEdit(id, namaPetugas, username) {
        document.getElementById('modalPetugasTitle').innerText = 'Edit Petugas';
        document.getElementById('formPetugas').action = baseUrlPetugas + '/update/' + id;
        document.getElementById('nama_petugas').value = namaPetugas;
        document.getElementById('username').value = username;
        document.getElementById('password').value = '';
        document.getElementById('password').required = false;
        document.getElementById('passwordHint').innerText = 'Kosongkan jika tidak ingin mengubah password.';
    }

    function hapusPetugas(id, namaPetugas) {
        Swal.fire({
            title: 'Hapus petugas?',
            text: 'Data petugas "' + namaPetugas + '" akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapusPetugas');
                form.action = baseUrlPetugas + '/delete/' + id;
                form.submit();
            }
        });
    }
</script>