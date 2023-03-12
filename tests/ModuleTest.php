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

    protected string $pathConfig;

    protected function setUp(): void
    {
        $this->pathConfig = __DIR__ . '\\ModuleFakeConfig.json';

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
        unlink($this->pathConfig);
        ModuleFake::dropTable();
    }

    public function test_initializeJsonConfig_shouldCreateJsonFileBasedOnObject(): void
    {
        $expected = '{"title":"Test Module",'
                    . '"status":100,'
                    . '"data":{"param1":"value1",'
                    . '"param2":"value2",'
                    . '"param3":"value3"},'
                    . '"pathConfig":"' . preg_replace('~[\\\]+~', '\\\\\\', $this->pathConfig) . '",'
                    . '"pathModule":"' . preg_replace('~[\\\]+~', '\\\\\\', __DIR__ . '\\fakes') . '"}';

        $this->moduleFake->setPathConfig($this->pathConfig)
                            ->initializeJsonConfig();

        $actual = file_get_contents($this->pathConfig);

        $this->assertEquals($expected, $actual);
    }

    public function test_createTable_shouldCreateATableWithModelFields(): void
    {
        $this->assertEquals(true, ModuleFake::createTable());
    }

    public function test_addAndGetDataDB(): void
    {
        ModuleFake::createTable();

        $this->moduleFake->setPathConfig($this->pathConfig);
        $this->moduleFake->setPathModule($this->moduleFake->getPathModule());
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
        $obj = ModuleFake::initializeObject('Test initializeObject');
        $this->assertInstanceOf(ModuleFake::class, ModuleFake::initializeObject('Test initializeObject'));
        unlink($obj->getPathConfig());
    }
}
