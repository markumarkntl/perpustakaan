<?php $__layoutVars = get_defined_vars(); ?>
<?= view('layouts/partials/header', $__layoutVars) ?>
<?= view('layouts/partials/body', $__layoutVars) ?>
<?= view('layouts/partials/sidebar', $__layoutVars) ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 font-bold"><?= esc($nama_petugas ?? 'Petugas') ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
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
