<?php

    $item_class ??= 'icon-module';
    $item_text ??= '';
    $item_activated ??= false;
    $request_url ??= '';

?>

<?php if ($item_activated === true) : ?>
<li class="activated">
<?php else : ?>
<li>
<?php endif; ?>
    <div class="<?= $item_class ?>"><i></i></div>
    <data value="<?= $request_url ?>"><?= $item_text ?></data>
</li>

