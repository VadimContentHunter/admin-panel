<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel\interfaces\IBlockLevel;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BlockLevel extends ActiveRecord implements IBlockLevel
{
    protected ?int $pageId;
    protected ?int $blockId;
    protected ?int $level;

    public function setPageId(int $pageId): IBlockLevel
    {
        $this->pageId = $pageId;
        return $this;
    }

    public function getPageId(): int
    {
        return $this->pageId;
    }

    public function setBlockId(int $blockId): IBlockLevel
    {
        $this->blockId = $blockId;
        return $this;
    }

    public function getBlockId(): int
    {
        return $this->blockId;
    }

    public function setLevel(int $level): IBlockLevel
    {
        $this->level = $level;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function changeLevel(int $new_level): IBlockLevel
    {
        $this->level = $new_level;
        return $this;
    }

    public static function getTableName(): string
    {
        return 'block_level';
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery((new TableMySQLQueryBuilder())
                        ->create(self::getTableName())
                            ->addField('pageId', FieldDataType::INT, [
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
                            ]))
                ->send();
            return true;
        }
        return false;
    }
}
