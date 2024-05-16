<?php

$infos = Exif::getInfo($image);
?>
<h1>Informations</h1>
<div>
<table cellpadding="3" border="1">
<?php
foreach ($infos as $info => $value) {
    ?>
    <tr>
        <th style="text-align: left">
            <?php echo $info; ?>
            <?php if ($info === 'Commentaire') { ?>
                <input type="button" value="Enregistrer" class="save-comment-clickable" data-url="/save-comment/<?php echo $image; ?>"/>
            <?php } ?>
        </th>
        <td style="text-align: left">
            <?php if ($info === 'Commentaire') { ?>
                <textarea rows="8" cols="60" id="comment"><?php echo $value; ?></textarea>
            <?php } else { ?>
                <?php echo $value; ?>
            <?php } ?>
        </td>
    </tr>
    <?php
}
?>
</table>
</div>
