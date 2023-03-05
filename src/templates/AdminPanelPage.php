<?php

$css_paths = $css_paths ?? [];
$js_head_paths = $js_head_paths ?? [];
$js_begin_body_paths = $js_begin_body_paths ?? [];
$js_after_body_paths = $js_after_body_paths ?? [];

$sidebar = $sidebar ?? '';
$header = $header ?? '';
$body_container = $body_container ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Sofia+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <?php foreach ($css_paths as $path) : ?>
        <link href="<?= $path ?>" rel="stylesheet">
    <?php endforeach; ?>
    <?php foreach ($js_head_paths as $path) : ?>
        <script src="<?= $path ?>"></script>
    <?php endforeach; ?>
    <title>Document</title>
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
