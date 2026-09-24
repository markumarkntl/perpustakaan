<?php
/** @var array $peminjamanList */
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

<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Transaksi Pengembalian</h5>
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
                                <th class="text-center">Buku Belum Kembali</th>
                                <th class="text-center">Status</th>
                                <th style="width: 170px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($peminjamanList)) : ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Tidak ada transaksi yang perlu dikembalikan.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($peminjamanList as $pinjam) : ?>
                                    <?php
                                        $collapseId = 'detail-kembali-' . $pinjam['id_peminjaman'];
                                        $telat      = strtotime($pinjam['tanggal_jatuh_tempo']) < strtotime(date('Y-m-d'));
                                    ?>
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
                                            <?php if ($telat) : ?>
                                                <span class="badge bg-danger">Terlambat</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="bukaModalKembalikanSemua(<?= (int) $pinjam['id_peminjaman'] ?>, '<?= esc($pinjam['nama_anggota'], 'js') ?>')">
                                                <i class="bi bi-box-arrow-in-left"></i> Kembalikan Semua
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="<?= $collapseId ?>">
                                        <td></td>
                                        <td colspan="9" class="p-0">
                                            <div class="p-3 bg-light">
                                                <table class="table table-sm table-bordered mb-0 bg-white">
                                                    <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Judul Buku</th>
                                                            <th class="text-center">Jumlah</th>
                                                            <th class="text-center" style="width: 140px;">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($pinjam['items'])) : ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted">Tidak ada item buku.</td>
                                                            </tr>
                                                        <?php else : ?>
                                                            <?php foreach ($pinjam['items'] as $item) : ?>
                                                                <tr>
                                                                    <td><?= esc($item['kode_buku']) ?></td>
                                                                    <td><?= esc($item['judul']) ?></td>
                                                                    <td class="text-center"><?= (int) $item['jumlah'] ?></td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                                            onclick="bukaModalKembalikan(<?= (int) $item['id_detail'] ?>, '<?= esc($item['judul'], 'js') ?>')">
                                                                            <i class="bi bi-box-arrow-in-left"></i> Kembalikan
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
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

<!-- Modal Kembalikan (per item) -->
<div class="modal fade" id="modalKembalikan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKembalikan" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Kembalikan Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Buku: <strong id="modalKembalikanJudul"></strong></p>
                    <label for="tanggal_kembali_item" class="form-label">Tanggal Kembali</label>
                    <input type="date" class="form-control" id="tanggal_kembali_item" name="tanggal_kembali" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kembalikan Semua (per transaksi) -->
<div class="modal fade" id="modalKembalikanSemua" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKembalikanSemua" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Kembalikan Semua Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Anggota: <strong id="modalKembalikanSemuaNama"></strong></p>
                    <label for="tanggal_kembali_semua" class="form-label">Tanggal Kembali</label>
                    <input type="date" class="form-control" id="tanggal_kembali_semua" name="tanggal_kembali" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Kembalikan Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const baseUrlPengembalian = "<?= base_url('pengembalian') ?>";

    // ==== Modal Kembalikan (per item) ====
    function bukaModalKembalikan(idDetail, judul) {
        document.getElementById('formKembalikan').action = baseUrlPengembalian + '/kembalikan/' + idDetail;
        document.getElementById('modalKembalikanJudul').innerText = judul;
        document.getElementById('tanggal_kembali_item').value = new Date().toISOString().slice(0, 10);

        const modal = new bootstrap.Modal(document.getElementById('modalKembalikan'));
        modal.show();
    }

    // ==== Modal Kembalikan Semua (per transaksi) ====
    function bukaModalKembalikanSemua(idPeminjaman, namaAnggota) {
        document.getElementById('formKembalikanSemua').action = baseUrlPengembalian + '/kembalikan-semua/' + idPeminjaman;
        document.getElementById('modalKembalikanSemuaNama').innerText = namaAnggota;
        document.getElementById('tanggal_kembali_semua').value = new Date().toISOString().slice(0, 10);

        const modal = new bootstrap.Modal(document.getElementById('modalKembalikanSemua'));
        modal.show();
    }
</script>