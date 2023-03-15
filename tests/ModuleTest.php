<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests;

use PDO;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\DB;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\tests\fakes\ModuleFake;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\models\Module\ModuleConfig;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleTest extends TestCase
{
    protected ModuleFake $moduleFake;

    protected DateTime $dataTime;

    protected string $pathConfig;

    protected function setUp(): void
    {
        $this->pathConfig = __DIR__ . '\\ModuleFakeConfig.json';

        $this->dataTime = new DateTime();
        $this->dataTime->setDate(2023, 3, 13);
        $this->dataTime->setTime(19, 51, 24);
        $this->moduleFake = new ModuleFake(
            (new ModuleConfig(ModuleFake::class)),
            $this->dataTime
        );
        $this->moduleFake->setAlias('Test Module')
                            ->setName('ModuleFake')
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
        $temp = new \stdClass();
        $temp->alias = "Test Module";
        $temp->name = "ModuleFake";
        $temp->status = 100;
        $temp->data = [
            "param1" => "value1",
            "param2" => "value2",
            "param3" => "value3"
        ];
        $temp->pathConfig = preg_replace('~[\\\]+~', '\\\\', $this->pathConfig);
        $temp->pathModule = preg_replace('~[\\\]+~', '\\\\', __DIR__ . '\\fakes');
        $temp->lastModifiedDateTime = $this->dataTime->format('Y-m-d H:i:s');
        $temp->formatDateTime = 'Y-m-d H:i:s';

        $json = json_encode($temp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(__DIR__ . '/test_initializeJsonConfig.json', $json, LOCK_EX);

        $expected = file_get_contents(__DIR__ . '/test_initializeJsonConfig.json');

        unlink(__DIR__ . '/test_initializeJsonConfig.json');

        // $expected = '{"alias":"Test Module",'
        //             . '"name":"ModuleFake",'
        //             . '"status":100,'
        //             . '"data":{"param1":"value1",'
        //             . '"param2":"value2",'
        //             . '"param3":"value3"'
        //             . '},'
        //             . '"pathConfig":"' . preg_replace('~[\\\]+~', '\\\\\\', $this->pathConfig) . '",'
        //             . '"pathModule":"' . preg_replace('~[\\\]+~', '\\\\\\', __DIR__ . '\\fakes') . '",'
        //             . '"lastModifiedDateTime":"' . $this->dataTime->format('Y-m-d H:i:s') . '",'
        //             . '"formatDateTime":"' . 'Y-m-d H:i:s'
        //             . '"}';

        $this->moduleFake->setLastModifiedDateTime($this->dataTime->format('Y-m-d H:i:s'));
        $this->moduleFake->setPathConfig($this->pathConfig);
        $this->moduleFake->initializeJsonConfig();

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

        $this->moduleFake->setLastModifiedDateTime($this->dataTime->format('Y-m-d H:i:s'));
        $this->moduleFake->setPathConfig($this->pathConfig);
        $this->moduleFake->setPathModule($this->moduleFake->getPathModule());
        $this->moduleFake->insertObjectToDb();

        $obj = ModuleFake::selectByField('path_config', $this->moduleFake->getPathConfig())[0] ?? null;
        $actual = null;

        if ($obj instanceof IModule) {
            $actual = $obj->getData();
        }

        $this->assertEquals($this->moduleFake->getData(), $actual);
    }

    public function test_initializeObject_shouldCreateAnObject(): void
    {
        ModuleFake::createTable();
        $module = new ModuleFake();
        $module->setAlias('Test initializeObject');
        $obj = $module->initializeReplaceThisObject();
        $this->assertInstanceOf(ModuleFake::class, $module);
        unlink($obj->getPathConfig());
    }

    public function test_app(): void
    {
        $data_time = new DateTime($this->dataTime->format('Y-m-d H:i:s'));
        $this->assertTrue(true);
    }
}
