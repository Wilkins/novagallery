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
            <a href="<?php echo Site::url().Page::data('parentPage'); ?>" class="text-muted link-back">&laquo; <?php Lang::p('Back'); ?></a>
        </div>
        <?php if (Mode::isEdition()): ?>
        <div class="col-4 mb-4" style="text-align: right">
            <a style="text-align:right" href="<?php echo Site::url().'duplicates/'.$album; ?>" target="duplicates" class="text-muted link-back">&#x1F50D; Trouver les doublons</a>
        </div>
        <div class="col-2 mb-4" style="text-align: right">
            <a style="text-align:right" href="<?php echo Site::url().'videos/'.$album; ?>" target="duplicates" class="text-muted link-back">&#x1F50D; Trouver les vidéos</a>
        </div>
        <?php endif; // mode edition ?>
      <?php endif; ?>
      <div class="container">
          <?php
          if ($album) {
              $cover = $gallery->hasCoverImage($album);
              if (! $cover) {
                  ?><p class="note">Vous devez choisir une cover pour cet album</p><?php
              }
              if (! $gallery->isWritable()) {
                  ?><p class="danger">Le répertoire n'est pas accessible en écriture</p><?php
              }
          }
          ?>        <!-- albums -->
        <?php if($gallery->isDeletable()): ?>
        <div class="text-center">
            <button class="deletealbum btn btn-danger center" data-url="/deletealbum/<?php echo $album; ?>/">
                Supprimer l'album vide
            </button>
        </div>
        <?php endif; ?>

        <?php

        if($gallery->hasAlbums()): ?>

        <div class="row px-2 mt-4">
          <?php $specialDirs = false; ?>
          <?php foreach($gallery->getAlbums($order) as $element => $modDate):
                $elementLink = rawurlencode($element);
                $elementPath = $album ? $album.'/'.$elementLink : $elementLink;
                if ($element === date('Y')+1) {
                    continue;
                }
          ?>
            <?php if ($specialDirs === false && File::isSpecialDir($elementLink)):
                $specialDirs = true;
                ?>
                </div>
                <div class="row px-2 mt-4">
            <?php endif; ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">
              <a href="<?php echo Site::basePath().'/album/'.$elementPath; ?>">
                  <?php if (File::isSpecialDir($elementLink)): ?>
                      <?php $cover =  THEME_PATH . "/assets/specials/$elementLink.png"; ?>
                  <?php else: ?>
                      <?php $cover = $gallery->coverImage($elementPath, $order); ?>
                  <?php endif; ?>
                <img src="<?php echo $cover ?>" loading="lazy" class="rounded" alt=""><br>
              </a>
                <?php if ($album): ?>
                <?php if (Mode::isEdition()): ?>
                <span class="icon rename-folder" style="float: right"
                      data-fullname="<?php echo $elementPath; ?>"
                      data-name="<?php echo $element; ?>"
                      data-url="<?php echo Site::basePath() . '/rename-folder/' . $elementPath; ?>">&#9998;
                </span>
                  <p class="cover-clickable" data-url="/cover/<?php echo $album; ?>/<?php echo $elementLink; ?>" title="Cover">
                      <i class="icon-cover-off icon">&#9733;</i>
                  </p>
                <?php endif; // Mode Edition ?>
                <?php endif; // is album ?>
                <?php echo Album::cleanAlbumTitle($element); ?>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>

        <!-- images -->
        <?php if ($gallery->hasImages()): ?>
            <div class="row gallery px-2 mt-4">
                <?php foreach ($gallery->images($order) as $element => $filedata):
                    $albumLink = Album::cleanAlbumName($album);
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">

                        <a href="<?php echo Album::urlLink($album, $element, $filedata, Site::config('imageSizeBig')); ?>"
                           target="_blank">
                            <div class="extension-overlay"
                                 data-ext="<?php echo strtoupper(pathinfo($element)['extension']); ?>"></div>
                            <?php if ($filedata[Metadata::FILETYPE_KEY] === 'video'): ?>
                                <div class="duration-overlay"
                                     data-ext="<?php echo $filedata[Metadata::DURATION_KEY]; ?>"><?php echo $filedata[Metadata::DURATION_KEY]; ?></div>
                            <?php endif; ?>
                            <img src="<?php echo Album::url($album, $element, $filedata, Site::config('imageSizeThumb')); ?>"
                                 loading="lazy" class="rounded" alt=""><br>
                        </a>
                        <div class="actions">
                            <p class="cover-clickable"
                               data-url="/cover/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Cover">
                                <i class="icon-cover-off icon">&#9733;</i>
                            </p>
                            <?php $favoriteFlag = isset($favorites[$element]); ?>
                            <p class="favorite-clickable"
                               data-url="/favorite/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Favorite">
                                <i class="icon-favorite-<?php echo $favoriteFlag ? 'on' : 'off'; ?> icon">&#9829;</i>
                            </p>
                            <p class="trash-clickable"
                               data-url="/trash/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Trash">
                                <span class="icon-trash-<?php echo $filedata[Metadata::TRASH_KEY] === 1 ? 'on' : 'off'; ?> icon">&#x2716;</span>
                            </p>
                            <p class="download-clickable"
                               data-url="/download/<?php echo $albumLink; ?>/<?php echo $element; ?>" title="Download">
                                <span class="icon-download-off icon">&#8615;</span>
                            </p>
                            <p class="rotateleft-clickable"
                               data-url="/rotateleft/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Rotate Left">
                                <span class="icon-rotateleft-off icon">&#x27F2;</span>
                            </p>
                            <p class="rotateright-clickable"
                               data-url="/rotateright/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Rotate Right">
                                <span class="icon-rotateright-off icon">&#x27F3;</span>
                            </p>
                            <p class="info-clickable"
                               data-url="/info/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Info">
                                <span class="icon-info-off icon">&#9432;</span>
                            </p>
                        </div>
                        <div class="actions-moves-work">
                            <?php foreach (File::getSpecialWorkActions() as $dir): ?>
                                <p class="moveto-clickable"
                                   data-url="/<?php echo $dir['url']; ?>/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                                   title="Move to <?php echo $dir['name']; ?>">
                                    <span class="icon-moveto icon"><img src="<?php echo $dir['icon']; ?>" alt="<?php echo $dir['name']; ?>"></span>
                                </p>
                            <?php endforeach ?>
                        </div>
                        <div class="actions-moves">
                            <?php foreach (File::getSpecialGroupActions() as $dir): ?>
                                <p class="moveto-clickable"
                                   data-url="/<?php echo $dir['url']; ?>/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                                   title="Move to <?php echo $dir['name']; ?>">
                                    <span class="icon-moveto icon"><img src="<?php echo $dir['icon']; ?>" alt="<?php echo $dir['name']; ?>"></span>
                                </p>
                            <?php endforeach ?>
                        </div>
                        <?php if (Mode::isEdition()): ?>
                        <?php endif; // Mode::isEdition() ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif; ?>
    </div>
</content>
