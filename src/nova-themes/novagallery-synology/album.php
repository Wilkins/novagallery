    <?php
      /** @var novaGalleryWithoutCache $gallery */
      $gallery = Page::data('gallery');
      $album = Page::data('album');
      $order = Page::data('order');
    $start = microtime(true);

    ?>
    <content class="row mt-0 mt-md-5">
      <div class="col-12 mb-1"><h1><?php echo Page::title(); ?></h1></div>
      <?php if($album): ?>
        <div class="col-12 mb-4"><a href="<?php echo Site::url().'/'.Page::data('parentPage'); ?>" class="text-muted link-back">&laquo; <?php L::p('Back'); ?></a></div>
      <?php endif; ?>
      <div class="container">
        <!-- albums -->
        <?php if($gallery->hasAlbums()): ?>
        <div class="row px-2 mt-4">
          <?php
          foreach($gallery->getAlbums($order) as $element => $modDate):

                $elementPath = $album ? $album.'/'.$element : $element;
          ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Site::basePath().'/album/'.$elementPath; ?>">
                <img src="<?php echo $gallery->coverImage($elementPath, $order);; ?>" loading="lazy" class="rounded"><br>
                <?php echo ucwords($element); ?>
              </a>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>

        <!-- images -->
        <?php if($gallery->hasImages()): ?>
        <div class="row gallery px-2 mt-4">
          <?php foreach($gallery->images($order) as $element => $modDate): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Synology::url($album, $element, Site::config('imageSizeBig')); ?>">
                <img src="<?php echo Synology::url($album, $element, Site::config('imageSizeThumb')); ?>" loading="lazy" class="rounded"><br>
              </a>
                <a href="/favorite/<?php echo $album; ?>/<?php echo $element; ?>">Favori</a>
                <?php echo $element; ?>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>
      </div>
    </content>
    <?php echo "Generated : ".(microtime(true)-$start). " secondes\n"; ?>
