<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\tests\fakes\PageFake;
use vadimcontenthunter\AdminPanel\tests\fakes\BlockFake;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\ForeignKeyAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel\BlockLevel;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BlockLevelFake extends BlockLevel
{
    public static function getTableName(): string
    {
        return 'block_level_test';
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery((new TableMySQLQueryBuilder())
                        ->create(static::getTableName())
                            ->addField('id', FieldDataType::INT, [
                                FieldAttributes::AUTO_INCREMENT,
                                FieldAttributes::PRIMARY_KEY
                            ])
                            ->addField('page_id', FieldDataType::INT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('block_id', FieldDataType::INT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('level', FieldDataType::INT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->consrtaintForeignKey(
                                'block_level_page_fk',
                                ['page_id'],
                                PageFake::getTableName(),
                                ['id'],
                                ForeignKeyAttributes::ON_DELETE,
                                ForeignKeyAttributes::ACTION_CASCADE
                            )
                            ->consrtaintForeignKey(
                                'block_level_block_fk',
                                ['block_id'],
                                BlockFake::getTableName(),
                                ['id'],
                                ForeignKeyAttributes::ON_DELETE,
                                ForeignKeyAttributes::ACTION_CASCADE
                            ))
                ->send();
            return true;
        }
        return false;
    }
}
