<?php

    $item_title ??= '';
    $item_date ??= '';
    $item_content ??= '';
    $has_push ??= false;
?>

<?php if ($has_push === true) : ?>
    <article class="__push">
<?php else : ?>
    <article>
<?php endif; ?>

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
