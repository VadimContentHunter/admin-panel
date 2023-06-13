<?php
    $title = is_string($title) ? $title : '';
    $blocks = is_array($blocks) ? $blocks : [];
    $blocks = array_filter($blocks, function (mixed $block) {
        if (is_string($block)) {
            return true;
        }
        return false;
    });
    ?>
<section class="container-separate">
    <h3><?= $title ?></h3>
</section>

<section class="container-menu">
    <menu>
        <li class="save">Сохранить</li>
        <li>Выбрать страницу</li>
        <li>Добавить блок</li>
        <li>Язык</li>
    </menu>
</section>

<section class="container-blocks">
    <ul class="list-blocks">
        <!-- <iframe src="http://admin-panel/admin/module/rest/view/BlockManagement/viewPageBlock/block/TestBlock/test-block"></iframe> -->
    <?php foreach ($blocks as $block) : ?>
        <li>
            <?= $block ?>
        </li>
    <?php endforeach; ?>
        <li class="default">
            <div class="icon-plus-solid">
                <i></i>
            </div>
        </li>
    </ul>
</section>
