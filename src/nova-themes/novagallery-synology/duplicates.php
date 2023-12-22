<?php

    $gallery = Page::data('gallery');
      $album = Page::data('album');
      $order = Page::data('order');
      $favorites = Page::data('favorites');
      $start = microtime(true);
    ?>

    <content class="row mt-0 mt-md-5">
      <div class="col-12 mb-1"><h1><?php echo Page::title(); ?></h1></div>
      <?php if($album): ?>
        <div class="col-12 mb-4"><a href="<?php echo Site::url().Page::data('parentPage'); ?>" class="text-muted link-back">&laquo; <?php Lang::p('Back'); ?></a></div>
      <?php endif; ?>
      <div class="container">
        <!-- albums -->
        <?php if($gallery->isDeletable()): ?>
        <div class="text-center">
            Aucune doublon trouvé
        </div>
        <?php endif; ?>

        <!-- images -->
        <?php if($gallery->hasImages()): ?>
            <div class="text-center">
                <a href="/duplicates/<?php echo $album; ?>" class="btn btn-info center">
                    Rafraîchir
                </a>
                <a href="?delete=1" class="btn btn-danger center">
                    Mettre les doublons à la Corbeille (<?php echo count($gallery->images()); ?>)
                </a>
            </div>
          <?php foreach($gallery->images() as $md5 => $elementList):
                $albumLink = Synology::cleanAlbumName($album);
                ?>
        <div class="row gallery px-2 mt-4">
            <?php foreach($elementList as $elementSource):
            $filedata = [Metadata::TRASH_KEY => '0'];
            $albumLocal = dirname($elementSource);
            $element = basename($elementSource);
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Synology::urlLink($albumLocal, $element, $filedata, Site::config('imageSizeBig')); ?>" target="_blank">
                <div class="extension-overlay" data-ext="<?php echo strtoupper(pathinfo($element)['extension']); ?>"></div>
                <img src="<?php echo Synology::url($albumLocal, $element, $filedata, Site::config('imageSizeThumb')); ?>" loading="lazy" class="rounded" alt=""><br>
              </a>
              <?php echo $elementSource; ?>
            </div>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
        <?php endif; ?>
      </div>
    </content>
    <?php # echo "Generated : ".(microtime(true)-$start). " secondes\n"; ?>
