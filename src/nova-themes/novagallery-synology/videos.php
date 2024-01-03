<?php

    $gallery = Page::data('gallery');
      $album = Page::data('album');
      $favorites = Page::data('favorites');
      $start = microtime(true);
    ?>

    <content class="row mt-0 mt-md-5">
      <div class="col-12 mb-1"><h1><?php echo Page::title(); ?></h1></div>
      <?php if($album): ?>
        <div class="col-6 mb-4">
            <a href="<?php echo Site::url().Page::data('parentPage'); ?>" class="text-muted link-back">&laquo; <?php Lang::p('Back'); ?></a>
        </div>
      <?php endif; ?>
      <div class="container">


        <!-- images -->
        <?php if ($gallery->hasImages()): ?>
            <div class="row gallery px-2 mt-4">
                <?php foreach ($gallery->images() as $element => $filedata):
                    //echo $element;
                    $albumLink = Album::cleanAlbumName($album);
                    $element = $filedata[Metadata::FULLNAME_KEY];
                    /*
                    print_R([
                        $element,
                        $filedata,
                        $album,
                        $albumLink
                    ]);
                    */
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5 element">

                        <a href="<?php echo Album::urlLink($album, $element, $filedata, Site::config('imageSizeBig')); ?>"
                           target="_blank">
                            <div class="extension-overlay"
                                 data-ext="<?php echo strtoupper(pathinfo($element)['extension']); ?>"></div>
                            <?php if ($filedata[Metadata::FILETYPE_KEY] === 'video'): ?>
                                <div class="duration-overlay"
                                     data-ext="<?php echo $filedata['duration']; ?>"><?php echo $filedata['duration']; ?></div>
                            <?php endif; ?>
                            <img src="<?php echo Album::url($album, $element, $filedata, Site::config('imageSizeThumb')); ?>"
                                 loading="lazy" class="rounded" alt=""><br>
                        </a>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif; ?>
    </div>
</content>
