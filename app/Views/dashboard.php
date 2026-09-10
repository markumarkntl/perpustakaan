<style>
    /* Styling khusus dashboard: bikin stat card lebih "hidup" (ada depth,
       gradient di icon, hover effect) supaya nggak keliatan flat. */
    .dashboard-stat-card {
        border-radius: 1rem;
        box-shadow: 0 6px 18px rgba(20, 24, 40, 0.06);
        transition: transform .2s ease, box-shadow .2s ease;
        overflow: hidden;
        position: relative;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(20, 24, 40, 0.12);
    }

    .dashboard-stat-card .card-body {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .dashboard-stat-icon {
        flex-shrink: 0;
        width: 3.4rem;
        height: 3.4rem;
        border-radius: .85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        box-shadow: 0 6px 14px -4px rgba(0, 0, 0, 0.35);
    }

    .dashboard-stat-icon.purple { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
    .dashboard-stat-icon.blue   { background: linear-gradient(135deg, #67e8f9, #0891b2); }
    .dashboard-stat-icon.green  { background: linear-gradient(135deg, #6ee7b7, #059669); }
    .dashboard-stat-icon.red    { background: linear-gradient(135deg, #fca5a5, #dc2626); }

    .dashboard-stat-label {
        margin-bottom: .15rem;
        font-size: .8rem;
        letter-spacing: .02em;
        text-transform: uppercase;
    }

    .dashboard-stat-value {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 0;
        line-height: 1.2;
    }
</style>

<p class="text-muted">Selamat datang, <?= esc($nama_petugas ?? 'Petugas') ?>.</p>

<section class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card dashboard-stat-card">
            <div class="card-body">
                <div class="dashboard-stat-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <h6 class="text-muted font-semibold dashboard-stat-label">Total Stok Buku</h6>
                    <h6 class="dashboard-stat-value"><?= (int) ($total_buku ?? 0) ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card dashboard-stat-card">
            <div class="card-body">
                <div class="dashboard-stat-icon blue">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h6 class="text-muted font-semibold dashboard-stat-label">Judul Buku</h6>
                    <h6 class="dashboard-stat-value"><?= (int) ($total_judul ?? 0) ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card dashboard-stat-card">
            <div class="card-body">
                <div class="dashboard-stat-icon green">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h6 class="text-muted font-semibold dashboard-stat-label">Total Anggota</h6>
                    <h6 class="dashboard-stat-value"><?= (int) ($total_anggota ?? 0) ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card dashboard-stat-card">
            <div class="card-body">
                <div class="dashboard-stat-icon red">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <h6 class="text-muted font-semibold dashboard-stat-label">Sedang Dipinjam</h6>
                    <h6 class="dashboard-stat-value"><?= (int) ($total_dipinjam ?? 0) ?></h6>
                </div>
            </div>
        </div>
    </div>
</section>