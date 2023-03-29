<?php

    $content ??= '';
    $grid_column_count ??= 0;
?>
<section class="container-base-style container-one" style="grid-column: span <?= $grid_column_count ?>">
    <?= $content ?>
</section>
