<?php $__layoutVars = get_defined_vars(); ?>
<?= view('layouts/partials/header', $__layoutVars) ?>
<?= view('layouts/partials/body', $__layoutVars) ?>
<?= view('layouts/partials/sidebar', $__layoutVars) ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                <?php
                    // Avatar dipilih otomatis dari koleksi foto bawaan template
                    // (assets/compiled/jpg/1.jpg s/d 8.jpg), berbeda-beda tiap
                    // petugas berdasarkan id_petugas supaya tidak selalu sama.
                    $avatarNumber = ((int) (session()->get('id_petugas') ?? 1) - 1) % 8;
                    $avatarFile   = ($avatarNumber < 0 ? $avatarNumber + 8 : $avatarNumber) + 1 . '.jpg';
                ?>
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="topbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex align-items-center">
                                <img alt="avatar" src="<?= base_url('assets/compiled/jpg/' . $avatarFile) ?>"
                                    class="rounded-circle" width="39" height="39" style="object-fit: cover;">
                                <div class="ms-2 text-end d-none d-sm-block">
                                    <h6 class="mb-0 user-dropdown-name font-bold"><?= esc($nama_petugas ?? 'Petugas') ?></h6>
                                    <p class="mb-0 text-sm text-gray-500 user-dropdown-status">@<?= esc(session()->get('username') ?? '-') ?></p>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="topbarUserDropdown">
                            <li><h6 class="dropdown-header">Halo, <?= esc($nama_petugas ?? 'Petugas') ?>!</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="page-heading">
                <h3><?= esc($title ?? 'Dashboard') ?></h3>
            </div>

            <div class="page-content">
                <?= $content ?? '' ?>
            </div>

            <?= view('layouts/partials/footer') ?>
        </div>
    </div>

<?= view('layouts/partials/scripts', $__layoutVars) ?>
</body>

</html>