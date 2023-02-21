<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\interfaces;

use vadimcontenthunter\AdminPane\view\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface ISitebarUi
{
    /**
     * @return array<IBaseUiComponent>
     */
    public function setLogo(string $logo_path): array;
}
