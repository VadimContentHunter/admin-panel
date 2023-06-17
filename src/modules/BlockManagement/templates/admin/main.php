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
        <li>
            <div class="select-element">
                <div class="item-selected">
                    <p>Выберите страницу</p>
                    <div class="icon-triangle">
                        <i></i>
                    </div>
                </div>
                <menu>
                    <!-- <li value="1">[id: 1] Страница 1</li> -->
                    <!-- <li value="0">Выберите страницу</li>
                    <li value="1">[id: 1] Страница 1</li>
                    <li value="2">[id: 2] Страница 2</li>
                    <li value="3">[id: 3] Страница 3</li>
                    <li value="4">[id: 4] Страница 1</li> -->
                    <li class="sync">
                        <div class="sync-block">
                            <div class="icon-sync">
                                <i></i>
                            </div>
                        </div>
                    </li>
                </menu>
            </div>
        </li>
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
