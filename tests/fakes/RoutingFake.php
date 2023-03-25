<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\routing\interfaces\IRoute;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class RoutingFake extends Routing
{
    /**
     * @return IRoute[]
     */
    public function fakeGetRoutes(): array
    {
        return $this->routes;
    }
}
