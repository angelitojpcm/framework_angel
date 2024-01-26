<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo BASEPATH; ?>">
    <?php echo $app->loadMetaTags($d); ?>
    <title><?php echo isset($d->title) ? $d->title.' - '.get_sitename() : 'Bienvenido - '.get_sitename(); ?></title>
    <?php echo load_styles(); ?>
</head>

<body>
