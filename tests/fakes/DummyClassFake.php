<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DummyClassFake
{
    private string $foo;
    private string $bar;

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function setFoo(string $foo): void
    {
        $this->foo = $foo;
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function setBar(string $bar): void
    {
        $this->bar = $bar;
    }

    #[NotInDb]
    public function getBaz(): string
    {
        return 'baz';
    }
}
