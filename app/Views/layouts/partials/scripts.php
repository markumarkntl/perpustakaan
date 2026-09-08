<script src="<?= base_url('assets/static/js/components/dark.js') ?>"></script>
<script src="<?= base_url('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/apexcharts/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/sweetalert2/sweetalert2.min.js') ?>"></script> <!---penting jir-->
<script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/dashboard.js') ?>"></script>
<?php if (! empty($extraScripts)) : ?>
    <?php foreach ($extraScripts as $script) : ?>
        <?php if ($script === 'assets/extensions/sweetalert2/sweetalert2.min.js') : ?>
            <?php continue; // skip dupli sw2 ?>
        <?php endif; ?>
        <script src="<?= base_url($script) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>