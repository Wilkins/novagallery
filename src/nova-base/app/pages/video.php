<html lang="fr">
<link rel="stylesheet" type="text/css" href="/nova-themes/novagallery-synology/assets/style.css" />
<?php

$videoFile = IMAGES_URL_CODE.'/'.$video;
?>
    <div style="text-align: center; width: 100%; height: 700px;">
        <video controls autoplay>
            <source src="<?php echo $videoFile; ?>" type="video/mp4">
        </video>
    </div>
</html>
