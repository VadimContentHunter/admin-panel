<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Block;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\exceptions\BlockException;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel\BlockLevel;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\interfaces\IPage;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\interfaces\IBlock;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Page extends ActiveRecord implements IPage
{
    protected ?string $title;

    /**
     * @var IBlock[]
     */
    #[NotInDb]
    protected array $blocks = [];

    public function getTitle(): string
    {
        if ($this->title === null) {
            throw new BlockException('Имя должно быть указано.');
        }
        return $this->title;
    }

    public function setTitle(string $title): IPage
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return IBlock[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * @param IBlock[] $blocks
     */
    public function setBlocks(array $blocks): IPage
    {
        $this->blocks = $blocks;
        return $this;
    }

    public function addBlocks(IBlock $blocks): IPage
    {
        $this->blocks[] = $blocks;
        return $this;
    }

    public static function selectByIdWithBlocks(int $page_id): ?IPage
    {
        if (self::createTable() !== false) {
            return null;
        }

        $table_name_for_blocks = Block::getTableName();
        $table_name_for_block_level = BlockLevel::getTableName();

        $db = new DB();
        $objects = $db->transactionalRequests()
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(self::getTableName())
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
                Block::class,
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

    public static function getTableName(): string
    {
        return 'pages';
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
                            ->addField('title', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ]))
                ->send();
            return true;
        }
        return false;
    }
}
