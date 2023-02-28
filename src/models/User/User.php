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
    protected string $name;

    protected string $email;

    protected string $passwordHash;

    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $password = null,
    ) {
        if ($name !== null) {
            $this->setName($name);
        }

        if ($email !== null) {
            $this->setEmail($email);
        }

        if ($password !== null) {
            $this->setPasswordHash($password);
        }
    }

    public function setName(string $name): IUser
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): IUser
    {
        $this->email = $email;
        return $this;
    }

    public function setPasswordHash(string $password): IUser
    {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function validateByEmailAndPassword(): bool
    {
        $object = self::selectByEmailAndPassword($this->getEmail(), $this->getPasswordHash());
        return $object instanceof User ? true : false;
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public static function selectByEmailAndPassword(string $email, string $password_hash): ?IUser
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
                            ->and('password_hash=:password_hash')
            )
            ->setClassName(self::class)
            ->addParameter(':email', $email)
            ->addParameter(':password_hash', $password_hash)
            ->send()[0] ?? null;

        return $objects instanceof User ? $objects : null;
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery(
                    (new TableMySQLQueryBuilder())
                        ->create(self::getTableName())
                            ->addField('id', FieldDataType::INT, [
                                FieldAttributes::AUTO_INCREMENT,
                                FieldAttributes::PRIMARY_KEY
                            ])
                            ->addField('name', FieldDataType::TEXT, [
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
