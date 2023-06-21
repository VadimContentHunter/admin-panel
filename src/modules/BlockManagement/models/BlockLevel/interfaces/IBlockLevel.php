<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IBlockLevel
{
    public function setPageId(int $pageId): IBlockLevel;

    public function getPageId(): int;

    public function setBlockId(int $blockId): IBlockLevel;

    public function getBlockId(): int;

    public function setLevel(int $level): IBlockLevel;

    public function getLevel(): int;

    public function changeLevel(int $new_level): IBlockLevel;
}
