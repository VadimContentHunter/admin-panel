<?php

$css_paths ??= [];
$head_code_paths ??= [];
$js_head_paths ??=  [];

$page_title ??= '';

?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Sofia+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title><?= $page_title?></title>
    <?php foreach ($head_code_paths as $code_block) : ?>
        <?= $code_block ?>
    <?php endforeach; ?>
    <?php foreach ($css_paths as $path) : ?>
        <link href="<?= $path ?>" rel="stylesheet">
    <?php endforeach; ?>
    <?php foreach ($js_head_paths as $path) : ?>
        <script src="<?= $path ?>" type="module"></script>
    <?php endforeach; ?>