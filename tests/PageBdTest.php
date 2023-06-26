<?php

declare(strict_types=1);

namespace vadimcontenthunter;

use PDO;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\AdminPanel\tests\fakes\PageFake;
use vadimcontenthunter\AdminPanel\tests\fakes\BlockFake;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\tests\fakes\BlockLevelFake;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\interfaces\IPage;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class PageBdTest extends TestCase
{
    protected function setUp(): void
    {
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
        } catch (\Exception $e) {
            $this->markTestSkipped(
                'Пропуск теста из за отсутствия базы данных в удаленном окружении.'
            );
        }
    }

    protected function tearDown(): void
    {
        // Page::dropTable();
    }

    public function test_createTables(): void
    {
        BlockFake::createTable();
        BlockLevelFake::createTable();
        PageFake::createTable();
        $this->assertTrue(true);
    }

    public function test_addField_page(): void
    {
        $page = new PageFake();
        $page->setTitle('Document 2');
        $page->insertObjectToDb();
        $this->assertTrue(true);
    }

    public function test_addField_block(): void
    {
        $block = new BlockFake();
        $block->setName('Information block');
        $block->setDescription('Information block description');
        $block->setPathBlockView(AdminPanelSetting::getPathToTemplatesForModules('BlockManagement', 'blocks/TestBlock/test-block.php'));
        $block->setParameters([
            '<input type="text" name="test1" value="" />',
            '<input type="text" name="test2" value="" />'
        ]);
        $block->insertObjectToDb();
        $this->assertTrue(true);
    }

    public function test_addField_blockLevel(): void
    {
        $blockLevel = new BlockLevelFake();
        $blockLevel->setBlockId(1);
        $blockLevel->setPageId(1);
        $blockLevel->setLevel(1);
        $blockLevel->insertObjectToDb();

        $blockLevel->setBlockId(2);
        $blockLevel->setPageId(1);
        $blockLevel->setLevel(2);
        $blockLevel->insertObjectToDb();

        $blockLevel->setBlockId(1);
        $blockLevel->setPageId(3);
        $blockLevel->setLevel(1);
        $blockLevel->insertObjectToDb();

        $this->assertTrue(true);
    }

    public function test_joining_tables(): void
    {
        // $page = new PageFake();
        $result = PageFake::fakeSelectByIdWithBlocks(1);
        $this->assertTrue(true);
    }
}
