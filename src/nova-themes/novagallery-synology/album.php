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
        <div class="col-6 mb-4">
            <a href="<?php echo Site::url().'/'.Page::data('parentPage'); ?>" class="text-muted link-back">&laquo; <?php Lang::p('Back'); ?></a>
        </div>
        <div class="col-6 mb-4" style="text-align: right">
            <a style="text-align:right" href="<?php echo Site::url().'/duplicates/'.$album; ?>" target="duplicates" class="text-muted link-back">&#x1F50D; Trouver les doublons</a>
        </div>
      <?php endif; ?>
      <div class="container">
          <?php
          $cover = $gallery->hasCoverImage($album);
          if (! $cover) {
              ?><p class="note">Vous devez choisir une cover pour cet album</p><?php
          }
          ?>        <!-- albums -->
        <?php if($gallery->isDeletable()): ?>
        <div class="text-center">
            <button class="deletealbum btn btn-danger center" data-url="/deletealbum/<?php echo $album; ?>/">
                Supprimer l'album vide
            </button>
        </div>
        <?php endif; ?>

        <?php if($gallery->hasAlbums()): ?>

        <div class="row px-2 mt-4">
          <?php foreach($gallery->getAlbums($order) as $element => $modDate):
                $elementLink = rawurlencode($element);
                $elementPath = $album ? $album.'/'.$elementLink : $elementLink;
          ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Site::basePath().'/album/'.$elementPath; ?>">
                  <?php $cover = $gallery->coverImage($elementPath, $order); ?>
                <img src="<?php echo $cover ?>" loading="lazy" class="rounded alt=""><br>
              </a>
                  <p class="cover-clickable" data-url="/cover/<?php echo $album; ?>/<?php echo $elementLink; ?>" title="Cover">
                      <i class="icon-cover-off icon">&#9733;</i>
                  </p>
                <?php echo ucwords($element); ?>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>

        <!-- images -->
        <?php if($gallery->hasImages()): ?>
        <div class="row gallery px-2 mt-4">
          <?php foreach($gallery->images($order) as $element => $filedata):
              $albumLink = Synology::cleanAlbumName($album);
              ?>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">

              <a href="<?php echo Synology::urlLink($album, $element, $filedata, Site::config('imageSizeBig')); ?>" target="_blank">
                <div class="extension-overlay" data-ext="<?php echo pathinfo($element)['extension']; ?>"></div>
                <img src="<?php echo Synology::url($album, $element, $filedata, Site::config('imageSizeThumb')); ?>" loading="lazy" class="rounded" alt=""><br>
              </a>
                <p class="cover-clickable" data-url="/cover/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Cover">
                    <i class="icon-cover-off icon">&#9733;</i>
                </p>
                <?php $favoriteFlag = isset($favorites[$element]); ?>
                <p class="favorite-clickable" data-url="/favorite/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Favorite">
                    <i class="icon-favorite-<?php echo $favoriteFlag ? 'on' : 'off'; ?> icon">&#9829;</i>
                </p>
                <p class="trash-clickable" data-url="/trash/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Trash">
                    <span class="icon-trash-<?php echo $filedata['trash'] === 1 ? 'on' : 'off'; ?> icon">&#x2716;</span>
                </p>
                <p class="download-clickable" data-url="/download/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Download">
                    <span class="icon-download-off icon">&#8615;</span>
                </p>
                <p class="rotateleft-clickable" data-url="/rotateleft/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Rotate Left">
                    <span class="icon-rotateleft-off icon">&#x27F2;</span>
                </p>
                <p class="rotateright-clickable" data-url="/rotateright/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Rotate Right">
                    <span class="icon-rotateright-off icon">&#x27F3;</span>
                </p>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>
      </div>
    </content>
    <?php # echo "Generated : ".(microtime(true)-$start). " secondes\n"; ?>
