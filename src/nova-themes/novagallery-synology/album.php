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
        <div class="col-4 mb-4" style="text-align: right">
            <a style="text-align:right" href="<?php echo Site::url().'duplicates/'.$album; ?>" target="duplicates" class="text-muted link-back">&#x1F50D; Trouver les doublons</a>
        </div>
        <div class="col-2 mb-4" style="text-align: right">
            <a style="text-align:right" href="<?php echo Site::url().'videos/'.$album; ?>" target="duplicates" class="text-muted link-back">&#x1F50D; Trouver les vidéos</a>
        </div>
      <?php endif; ?>
      <div class="container">
          <?php
          $cover = $gallery->hasCoverImage($album);
          if (! $cover) {
              ?><p class="note">Vous devez choisir une cover pour cet album</p><?php
          }
          if (! $gallery->isWritable()) {
              ?><p class="danger">Le répertoire n'est pas accessible en écriture</p><?php
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
                      <?php $cover =  THEME_PATH . "/assets/$elementLink.png"; ?>
                  <?php else: ?>
                      <?php $cover = $gallery->coverImage($elementPath, $order); ?>
                  <?php endif; ?>
                <img src="<?php echo $cover ?>" loading="lazy" class="rounded alt=""><br>
              </a>
                <span class="icon rename-folder" style="float: right"
                      data-fullname="<?php echo $elementPath; ?>"
                      data-name="<?php echo $element; ?>"
                      data-url="<?php echo Site::basePath() . '/rename-folder/' . $elementPath; ?>">&#9998;
                        </span>
                  <p class="cover-clickable" data-url="/cover/<?php echo $album; ?>/<?php echo $elementLink; ?>" title="Cover">
                      <i class="icon-cover-off icon">&#9733;</i>
                  </p>
                <?php echo Synology::cleanAlbumTitle($element); ?>
            </div>
          <?php endforeach ?>
        </div>
        <?php endif; ?>

        <!-- images -->
        <?php if ($gallery->hasImages()): ?>
            <div class="row gallery px-2 mt-4">
                <?php foreach ($gallery->images($order) as $element => $filedata):
                    $albumLink = Synology::cleanAlbumName($album);
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">

                        <a href="<?php echo Synology::urlLink($album, $element, $filedata, Site::config('imageSizeBig')); ?>"
                           target="_blank">
                            <div class="extension-overlay"
                                 data-ext="<?php echo strtoupper(pathinfo($element)['extension']); ?>"></div>
                            <?php if ($filedata[Metadata::FILETYPE_KEY] === 'video'): ?>
                                <div class="duration-overlay"
                                     data-ext="<?php echo $filedata[Metadata::DURATION_KEY]; ?>"><?php echo $filedata[Metadata::DURATION_KEY]; ?></div>
                            <?php endif; ?>
                            <img src="<?php echo Synology::url($album, $element, $filedata, Site::config('imageSizeThumb')); ?>"
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
                        </div>
                        <div class="actions-moves-work">
                            <p class="moveto-clickable"
                               data-url="/moveto-ugap/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to UGAP">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/UGAP.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-ungi/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to UNGI">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/UNGI.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-celeste/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to CELESTE">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/CELESTE.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-houra/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to Houra">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/HOURA.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-openclassrooms/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to OpenClassrooms">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/OpenClassrooms.png"></span>
                            </p>
                        </div>
                        <div class="actions-moves">
                            <p class="moveto-clickable"
                               data-url="/moveto-snapchat/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to Snapchat">
                                <span class="icon-moveto icon"><img src="<?php echo THEME_PATH; ?>/assets/Snapchat.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-bestof/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to BestOf">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/BestOf.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-maison/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to Maison">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/Maison.png"></span>
                            </p>
                            <p class="moveto-clickable"
                               data-url="/moveto-divers/<?php echo $albumLink; ?>/<?php echo $element; ?>"
                               title="Move to Divers">
                                <span class="icon-moveto icon"><img
                                            src="<?php echo THEME_PATH; ?>/assets/Divers.png"></span>
                            </p>

                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif; ?>
    </div>
</content>
<?php # echo "Generated : ".(microtime(true)-$start). " secondes\n"; ?>
