<html lang="fr">
<link rel="stylesheet" type="text/css" href="/nova-themes/novagallery-synology/assets/style.css" />
<script type="text/javascript">
    function failedVideo(e) {
        switch (e.target.error.code) {
            case e.target.error.MEDIA_ERR_ABORTED:
                alert('You aborted the video playback.');
                break;
            case e.target.error.MEDIA_ERR_NETWORK:
                alert('A network error caused the video download to fail part-way.');
                break;
            case e.target.error.MEDIA_ERR_DECODE:
                alert('The video playback was aborted due to a corruption problem or because the video used features your browser did not support.');
                break;
            case e.target.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                alert('The video could not be loaded, either because the server or network failed or because the format is not supported.');
                break;
            default:
                alert('An unknown error occurred.');
                break;
        }
    }
</script>
<?php

$videoFile = IMAGES_URL_CODE.'/'.$video;
?>
    <div style="text-align: center; width: 100%; height: 700px;">
        <video controls autoplay onerror="failedVideo(event)">
            <source src="<?php echo $videoFile; ?>" type2="video/mp4" >
        </video>
    </div>
</html>
