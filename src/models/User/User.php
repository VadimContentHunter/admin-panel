<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\User;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class User extends ActiveRecord implements IUser
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $email = null,
        protected ?string $passwordHash = null,
    ) {
    }

    public function setName(string $name): IUser
    {
        return $this;
    }

    public function setEmail(string $email): IUser
    {
        return $this;
    }

    public function setPasswordHash(string $password): IUser
    {
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function validateByNameAndPassword(): bool
    {
        return false;
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public static function selectByEmailAndPassword(string $email, string $password): ?IUser
    {
        if (self::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objects = $db->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(self::getTableName())
                            ->where('email=:email')
                            ->and('password=:password')
            )
            ->setClassName(self::class)
            ->addParameter(':email', $email)
            ->addParameter(':password', $password)
            ->send()[0] ?? null;

        return $objects instanceof User ? $objects : null;
    }

    public static function createTable(): bool
    {
        if (!self::isTableName((DB::$connector?->getDatabaseName()) ?? '')) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery(
                    (new TableMySQLQueryBuilder())
                        ->create(self::getTableName())
                            ->addField('id', FieldDataType::INT, [
                                FieldAttributes::AUTO_INCREMENT,
                                FieldAttributes::PRIMARY_KEY
                            ])
                            ->addField('username', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('email', FieldDataType::getTypeVarchar(80), [
                                FieldAttributes::NOT_NULL,
                                FieldAttributes::UNIQUE
                            ])
                            ->addField('password_hash', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                )
                ->send();

            return true;
        }
        return false;
    }
}
