<?php

    $item_title ??= '';
    $item_date ??= '';
    $item_content ??= '';

    $has_push ??= false;
    $has_default ??= false;
    $has_push = is_bool($has_push) && $has_push ? '__push' : '';
    $has_default = is_bool($has_default) && $has_default ? 'default' : '';
?>

<article class="<?= $has_push ?><?= $has_push ? ' ' . $has_default : $has_default?>">

    <h4>
        <p>
            <?= $item_title ?>
            <time datetime="<?= $item_date ?>"><?= $item_date ?></time>
        </p>
        <menu>
            <button class="check">
                <div class="icon-check">
                    <i></i>
                </div>
            </button>
        </menu>
    </h4>
    <a>
        <?= $item_content ?>
    </a>
</article>
