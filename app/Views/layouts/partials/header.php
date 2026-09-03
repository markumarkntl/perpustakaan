<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - Perpustakaan</title>

    <link rel="shortcut icon" href="<?= base_url('assets/compiled/svg/favicon.svg') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/iconly.css') ?>">
    <?php if (! empty($extraStyles)) : ?>
        <?php foreach ($extraStyles as $style) : ?>
            <link rel="stylesheet" href="<?= base_url($style) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>