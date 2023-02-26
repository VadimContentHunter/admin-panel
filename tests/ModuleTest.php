<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\AdminPanel\tests\fakes\ModuleFake;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleTest extends TestCase
{
    public function test_initializeJsonConfig_shouldCreateJsonFileBasedOnObject(): void
    {
        $expected = '{"title":"Test Module",'
                    . '"status":100,'
                    . '"data":{"param1":"value1",'
                    . '"param2":"value2",'
                    . '"param3":"value3"},'
                    . '"pathConfig":"D:\\\OpenServer\\\domains\\\admin-panel\\\tests\\\ModuleFakeConfig.json"}';

        $path = __DIR__ . '\\ModuleFakeConfig.json';
        $moduleFake = new ModuleFake();
        $moduleFake->setTitle('Test Module')
                    ->setStatus(StatusCode::ON)
                    ->setPathConfig($path)
                    ->setData([
                        'param1' => 'value1',
                        'param2' => 'value2',
                        'param3' => 'value3',
                    ])
                    ->initializeJsonConfig();

        $actual = file_get_contents($path);
        unlink($path);

        $this->assertEquals($expected, $actual);
    }
}
