<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\AdminPanel\routing\Routing;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class RoutingFake extends Routing
{
    public function fakeGetRoutes(): array
    {
        return $this->routes;
    }
}
