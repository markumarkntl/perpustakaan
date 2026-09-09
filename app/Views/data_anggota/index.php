<?php
/** @var array $anggotaList */


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
                <h5 class="mb-0">Daftar Anggota</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAnggota" onclick="bukaModalTambah()">
                    <i class="bi bi-plus-lg"></i> Tambah Anggota
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($anggotaList)) : ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada data anggota.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($anggotaList as $anggota) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($anggota['nis']) ?></td>
                                        <td><?= esc($anggota['nama']) ?></td>
                                        <td><?= esc($anggota['kelas']) ?></td>
                                        <td><?= esc($anggota['no_hp'] ?? '-') ?></td>
                                        <td><?= esc($anggota['alamat'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAnggota"
                                                onclick='bukaModalEdit(<?= json_encode($anggota) ?>)'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="hapusAnggota(<?= (int) $anggota['id_anggota'] ?>, '<?= esc($anggota['nama'], 'js') ?>')">
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

<!-- Modal Tambah / Edit Anggota -->
<div class="modal fade" id="modalAnggota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAnggota" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnggotaTitle">Tambah Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required maxlength="120">
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
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

<form id="formHapusAnggota" method="post" action="" class="d-none">
    <?= csrf_field() ?>
</form>

<script>
    const baseUrlDataAnggota = "<?= base_url('data-anggota') ?>";

    function bukaModalTambah() {
        document.getElementById('modalAnggotaTitle').innerText = 'Tambah Anggota';
        document.getElementById('formAnggota').action = baseUrlDataAnggota + '/store';
        document.getElementById('formAnggota').reset();
    }

    function bukaModalEdit(anggota) {
        document.getElementById('modalAnggotaTitle').innerText = 'Edit Anggota';
        document.getElementById('formAnggota').action = baseUrlDataAnggota + '/update/' + anggota.id_anggota;
        document.getElementById('nis').value    = anggota.nis;
        document.getElementById('nama').value   = anggota.nama;
        document.getElementById('kelas').value  = anggota.kelas;
        document.getElementById('no_hp').value  = anggota.no_hp ?? '';
        document.getElementById('alamat').value = anggota.alamat ?? '';
    }

    function hapusAnggota(id, nama) {
        Swal.fire({
            title: 'Hapus anggota?',
            text: 'Data anggota "' + nama + '" akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapusAnggota');
                form.action = baseUrlDataAnggota + '/delete/' + id;
                form.submit();
            }
        });
    }
</script>