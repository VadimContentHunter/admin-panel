<?php

    $item_class ??= 'icon-module';
    $item_text ??= '';
    $item_activated ??= false;

?>

<?php if ($item_activated === true) : ?>
<li class="activated">
    <div class="<?= $item_class ?>"><i></i></div>
    <p><?= $item_text ?></p>
</li>

<?php else : ?>
<li>
    <div class="<?= $item_class ?>"><i></i></div>
    <p><?= $item_text ?></p>
</li>
<?php endif; ?>

