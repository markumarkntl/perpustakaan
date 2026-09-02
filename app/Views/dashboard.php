<p class="text-muted">Selamat datang, <?= esc($nama_petugas ?? 'Petugas') ?>.</p>

<section class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12">
                        <h6 class="text-muted font-semibold">Total Stok Buku</h6>
                        <h6 class="font-extrabold mb-0"><?= (int) ($total_buku ?? 0) ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="bi bi-book-half"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12">
                        <h6 class="text-muted font-semibold">Judul Buku</h6>
                        <h6 class="font-extrabold mb-0"><?= (int) ($total_judul ?? 0) ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 d-flex justify-content-start">
                        <div class="stats-icon green mb-2">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12">
                        <h6 class="text-muted font-semibold">Total Anggota</h6>
                        <h6 class="font-extrabold mb-0"><?= (int) ($total_anggota ?? 0) ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 d-flex justify-content-start">
                        <div class="stats-icon red mb-2">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12">
                        <h6 class="text-muted font-semibold">Sedang Dipinjam</h6>
                        <h6 class="font-extrabold mb-0"><?= (int) ($total_dipinjam ?? 0) ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
