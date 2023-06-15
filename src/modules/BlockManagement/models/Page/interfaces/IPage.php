<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\interfaces;

use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\interfaces\IBlock;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IPage
{
    public function getTitle(): string;

    public function setTitle(string $title): IPage;

    /**
     * @return IBlock[]
     */
    public function getBlocks(): array;

    /**
     * @param IBlock[] $blocks
     */
    public function setBlocks(array $blocks): IPage;

    public function addBlocks(IBlock $blocks): IPage;
}
