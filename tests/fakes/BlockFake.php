<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Block;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BlockFake extends Block
{
    public static function getTableName(): string
    {
        return 'blocks_test';
    }
}
