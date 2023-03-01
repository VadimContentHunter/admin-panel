<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests;

use PDO;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\DB;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\tests\fakes\ModuleFake;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleTest extends TestCase
{
    protected ModuleFake $moduleFake;

    protected string $path;

    protected function setUp(): void
    {
        $this->path = __DIR__ . '\\ModuleFakeConfig.json';

        $this->moduleFake = new ModuleFake();
        $this->moduleFake->setTitle('Test Module')
                            ->setStatus(StatusCode::ON)
                            ->setData([
                                'param1' => 'value1',
                                'param2' => 'value2',
                                'param3' => 'value3',
                            ]);

        try {
            DB::$connector = new Connector(
                typeDb: 'mysql',
                host: 'localhost',
                user: 'mytest',
                password: 'mytest',
                dbName: 'db_admin_panel',
                options: [
                        PDO::ATTR_PERSISTENT => true
                    ]
            );

            DB::$connector->connect();
            ModuleFake::dropTable();
        } catch (\Exception $e) {
            $this->markTestSkipped(
                'Пропуск теста из за отсутствия базы данных в удаленном окружении.'
            );
        }
    }

    protected function tearDown(): void
    {
        unlink($this->path);
        ModuleFake::dropTable();
    }

    public function test_initializeJsonConfig_shouldCreateJsonFileBasedOnObject(): void
    {
        $expected = '{"title":"Test Module",'
                    . '"status":100,'
                    . '"data":{"param1":"value1",'
                    . '"param2":"value2",'
                    . '"param3":"value3"},'
                    . '"pathConfig":"' . preg_replace('~[\\\]+~', '\\\\\\', $this->path) . '"}';

        $this->moduleFake->setPathConfig($this->path)
                            ->initializeJsonConfig();

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    public function test_createTable_shouldCreateATableWithModelFields(): void
    {
        $this->assertEquals(true, ModuleFake::createTable());
    }

    public function test_addAndGetDataDB(): void
    {
        ModuleFake::createTable();

        $this->moduleFake->setPathConfig($this->path)->initializeJsonConfig();
        $this->moduleFake->insertObjectToDb();

        $obj = ModuleFake::selectByField('title', $this->moduleFake->getTitle())[0] ?? null;
        $actual = null;

        if ($obj instanceof IModule) {
            $actual = $obj->getData();
        }

        $this->assertEquals($this->moduleFake->getData(), $actual);
    }

    public function test_initializeObject_shouldCreateAnObject(): void
    {
        ModuleFake::createTable();

        $this->assertInstanceOf(ModuleFake::class, ModuleFake::initializeObject('Test initializeObject'));
    }
}
