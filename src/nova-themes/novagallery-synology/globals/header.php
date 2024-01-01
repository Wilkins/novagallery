<!DOCTYPE HTML>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?php echo Page::metaTitle(); ?></title>
  <meta name="description" content="<?php echo Page::metaDescription(); ?>">

  <?php $cssmtime = filemtime(__DIR__.'/../assets/style.css'); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/assets/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/assets/simple-lightbox.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo THEME_PATH; ?>/assets/style.css?t=<?php echo $cssmtime; ?>" />

  <link rel="icon" href="<?php echo THEME_PATH; ?>/assets/novagallery-favicon.png" type="image/png">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo Site::url(); ?>"><?php echo Site::config('siteName'); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mx-auto" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <?php if (Mode::isNavigation()): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Site::url(); ?>mode/edition">Mode Édition</a>
            </li>
            <?php endif; ?>
            <?php if (Mode::isEdition()): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Site::url(); ?>mode/navigation">Mode Navigation</a>
            </li>
            <?php endif; ?>
        </ul>
        <form class="form-inline">
            <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
        </form>
    </div>
</nav>

  <div class="container">
    <header class="row mt-4">
     <div class="col-12 col-md-9 mb-3 text-md-right">
       <?php if (Site::config('pagePassword') && isset($_SESSION['visitorLoggedIn']) && $_SESSION['visitorLoggedIn'] === true): ?>
        <a href="<?php echo Site::url().'/logout' ?>" class="btn btn-secondary btn-sm d-none d-md-inline-block"><?php Lang::p('Logout'); ?></a>
      <?php endif; ?>
     </div>
    </header>
