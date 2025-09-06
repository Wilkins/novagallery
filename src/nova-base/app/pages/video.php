<html lang="fr">
<link rel="stylesheet" type="text/css" href="/nova-themes/novagallery-synology/assets/style.css" />
<?php

$videoFile = GalleryVideo::getVideo($video);
$videoFile2 = GalleryVideo::getVideo2($video);
?>
    <div style="text-align: center; width: 100%; height: 700px;">
        <video controls autoplay >
            <source src="<?php echo $videoFile; ?>">
        </video>
<!--
        <video controls autoplay >
            <source src="<?php echo $videoFile2; ?>">
        </video>
-->
    </div>
</html>
