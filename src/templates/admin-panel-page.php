<?php

$css_paths ??= [];
$js_head_paths ??= [];
$js_begin_body_paths ??= [];
$js_after_body_paths ??= [];

$sidebar ??= '';
$header ??= '';
$body_container ??= '';
$head ??= '';

?>
<!DOCTYPE html>
<html>
<head>
    <?= $head?>
</head>
<body>
    <?php foreach ($js_begin_body_paths as $path) : ?>
        <script src="<?= $path ?>"></script>
    <?php endforeach; ?>
    <div class="main-block">
        <?= $sidebar ?>
        <main>
            <?= $header ?>
            <section class="content-wrapper">
                <?= $body_container ?>
            </section>
        </main>
    </div>
    <?php foreach ($js_after_body_paths as $path) : ?>
        <script src="<?= $path ?>"></script>
    <?php endforeach; ?>
</body>
</html>
