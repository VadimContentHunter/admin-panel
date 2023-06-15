<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\tests\fakes\BlockLevelFake;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\Page;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Block;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\interfaces\IPage;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class PageFake extends Page
{
    public static function getTableName(): string
    {
        return 'pages_test';
    }

    public static function selectByIdWithBlocks(int $page_id): ?IPage
    {
        if (self::createTable() !== false) {
            return null;
        }

        $table_name_for_blocks = BlockFake::getTableName();
        $table_name_for_block_level = BlockLevelFake::getTableName();

        $db = new DB();
        $objects = $db->transactionalRequests()
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(PageFake::getTableName())
                        ->where('id=:page_id'),
                self::class,
                parameters: [
                    ':page_id' => [$page_id],
                ]
            )
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField($table_name_for_blocks . '.id')
                        ->addField($table_name_for_blocks . '.name')
                        ->addField($table_name_for_blocks . '.description')
                        ->addField($table_name_for_blocks . '.parameters')
                        ->addField($table_name_for_blocks . '.path_block_view')
                        ->from($table_name_for_block_level)
                            ->innerJoin($table_name_for_blocks)
                            ->on($table_name_for_blocks . '.id = ' . $table_name_for_block_level . '.block_id')
                            ->where($table_name_for_block_level . '.page_id=:page_id'),
                BlockFake::class,
                parameters: [
                    ':page_id' => [$page_id],
                ]
            )
            ->send();

        $result = null;
        if ($objects[0][0] instanceof IPage) {
            $result = $objects[0][0];

            if (isset($objects[1])) {
                $result->setBlocks($objects[1]);
                return $result;
            }
        }
        return null;
    }
}
