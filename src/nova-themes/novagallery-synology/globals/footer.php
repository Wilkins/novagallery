    <footer class="row mt-5 mb-5 m">
      <div class="mx-auto mb-4">
        <?php if(Site::config('pagePassword') && isset($_SESSION['visitorLoggedIn']) && $_SESSION['visitorLoggedIn'] === true): ?>
        <a href="<?php echo Site::url().'/logout' ?>" class="btn btn-secondary d-md-none"><?php Lang::p('Logout'); ?></a>
      <?php endif; ?>
      </div>
        <?php /*
      <div class="col-12 text-center text-secondary footerText"><?php echo Site::config('footerText'); ?></div>
      */ ?>
    </footer>  
  </div>

    <?php $jsmtime = filemtime(__DIR__.'/../assets/novagallery.js'); ?>
  <script type="text/javascript" src="<?php echo THEME_PATH; ?>/assets/simple-lightbox.min.js"></script>
  <script type="text/javascript" src="<?php echo THEME_PATH; ?>/assets/jquery-3.6.4.min.js"></script>
  <script type="text/javascript" src="<?php echo THEME_PATH; ?>/assets/novagallery.js?t=<?php echo $jsmtime; ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
</html>
