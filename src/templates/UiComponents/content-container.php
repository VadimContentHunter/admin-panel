<?php

    $content ??= '';
    $grid_column_count ??= 0;
    $html_scripts ??= [];
?>
<section class="container-base-style container-one" style="grid-column: span <?= $grid_column_count ?>">
    <?= $content ?>
    <?php foreach ($html_scripts as $fragment) : ?>
        <?= $fragment ?>
    <?php endforeach; ?>
</section>
