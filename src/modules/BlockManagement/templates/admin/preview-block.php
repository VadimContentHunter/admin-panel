<?php

    $body = is_string($body) ? $body : '';
    $css_paths = is_array($css_paths) ? $css_paths : [];
    $css_paths = array_filter($css_paths, function (mixed $path) {
        if (is_string($path)) {
            return true;
        }
        return false;
    });
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach ($css_paths as $path) : ?>
        <link href="<?= $path ?>" rel="stylesheet">
    <?php endforeach; ?>
    <title>preview-block</title>
</head>
<body>
    <?= $body ?>
</body>
</html>
