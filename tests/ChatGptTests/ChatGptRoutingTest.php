<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\ChatGptTests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\AdminPanel\routing\Route;
use vadimcontenthunter\AdminPanel\tests\fakes\RoutingFake;
use vadimcontenthunter\AdminPanel\tests\fakes\TestControllerFake;

class ChatGptRoutingTest extends TestCase
{
    protected mixed $testController;

    protected function setUp(): void
    {
        $this->testController = new class {
            public function testMethod(): void
            {
                echo 'completed.';
            }
        };
    }

    public function testAddRoute(): void
    {
        $routing = new RoutingFake();
        $routing->addRoute('~/test~', $this->testController::class, 'testMethod');
        $this->assertCount(1, $routing->fakeGetRoutes());
    }

    public function testStart(): void
    {
        $routing = new RoutingFake();
        $routing->addRoute('~/test~', $this->testController::class, 'testMethod');
        // ob_start();
        $routing->start('/test');
        // $output = ob_get_clean();
        $this->expectOutputString('completed.');
    }

    public function testStartNotFound(): void
    {
        $routing = new RoutingFake();
        $routing->addRoute('~/test~', $this->testController::class, 'testMethod');
        $routing->start('/wrong_url');
        $this->expectOutputString('Страница не найдена!');
    }

    public function testSearchByPattern(): void
    {
        $routing = new RoutingFake();
        $route = new Route('~/test~', $this->testController::class, 'testMethod');
        $routing->addRoute('~/test~', $this->testController::class, 'testMethod');
        $this->assertEquals($route, $routing->searchByPattern('/test'));
    }
}
