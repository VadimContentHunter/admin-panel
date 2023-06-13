<?php

    $input_blocks = is_array($input_blocks) ? $input_blocks : [];
    $input_blocks = array_filter($input_blocks, function (mixed $block) {
        if (is_string($block)) {
            return true;
        }
        return false;
    });
    ?>

<h4>BLOCK 1</h4>
<details>
    <summary>Описание</summary>
    <p>
        HTML блок - это структурный элемент в веб-разработке, который используется для создания секций на веб-странице.
        Он может содержать различные виды контента, такие как текст, изображения, видео, таблицы и другие элементы. HTML
        блоки можно стилизовать с помощью CSS, добавляя разные визуальные эффекты и оформление для достижения желаемого
        вида и отображения на веб-странице. Структурированные HTML блоки делают веб-страницы более читабельными и легко
        поддающимися редактированию.
    </p>
</details>
<?php foreach ($input_blocks as $block) : ?>
    <?= $block ?>
<?php endforeach; ?>

