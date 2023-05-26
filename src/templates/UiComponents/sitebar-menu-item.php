<?php

    $item_class ??= 'icon-module';
    $item_text ??= '';
    $item_activated ??= false;
    $value_data ??= '';

?>

<?php if ($item_activated === true) : ?>
<li class="activated">
<?php else : ?>
<li>
<?php endif; ?>
    <div class="<?= $item_class ?>"><i></i></div>
    <data value="<?= $value_data ?>"><?= $item_text ?></data>
</li>

