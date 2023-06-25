<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel;

use vadimcontenthunter\AdminPanel\modules\BlockManagement\exceptions\BlockException;
use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\ForeignKeyAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\Page;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Block;
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
        if (!is_int($pageId)) {
            throw new BlockException('BlockLevel должен иметь page Id');
        }

        $this->pageId = $pageId;
        return $this;
    }

    public function getPageId(): int
    {
        if (!is_int($this->pageId)) {
            throw new BlockException('BlockLevel должен иметь page Id');
        }

        return $this->pageId;
    }

    public function setBlockId(int $blockId): IBlockLevel
    {
        $this->blockId = $blockId;
        return $this;
    }

    public function getBlockId(): int
    {
        if (!is_int($this->blockId)) {
            throw new BlockException('BlockLevel должен иметь block Id');
        }

        return $this->blockId;
    }

    public function setLevel(int $level): IBlockLevel
    {
        $this->level = $level;
        return $this;
    }

    public function getLevel(): int
    {
        if (!is_int($this->level)) {
            throw new BlockException('BlockLevel должен иметь level');
        }

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
                                Page::getTableName(),
                                ['id'],
                                ForeignKeyAttributes::ON_DELETE,
                                ForeignKeyAttributes::ACTION_CASCADE
                            )
                            ->consrtaintForeignKey(
                                'block_level_block_fk',
                                ['block_id'],
                                Block::getTableName(),
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
