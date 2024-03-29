<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\User;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
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
    protected string $name = '';
    protected string $email = '';
    protected string $passwordHash = '';
    public function __construct(?string $name = null, ?string $email = null, ?string $password_hash = null,)
    {
        if ($name !== null) {
            $this->setName($name);
        }

        if ($email !== null) {
            $this->setEmail($email);
        }

        if ($password_hash !== null) {
            $this->setPasswordHash($password_hash);
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

    public function setPasswordHash(string $password_hash): IUser
    {
        $this->passwordHash = $password_hash;
        return $this;
    }

    public function setPasswordHashFromPassword(string $password): IUser
    {
        $this->passwordHash = self::composePasswordHash($password);
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
        $object = self::selectByEmailAndPasswordHash($this->getEmail(), $this->getPasswordHash());
        return $object instanceof User ? true : false;
    }

    public function setSessionFromObject(): void
    {
        $_SESSION["ss_user_email"] = $this->getEmail();
        $_SESSION["ss_user_password_hash"] = $this->getPasswordHash();
    }

    public static function deleteSessionData(): void
    {
        unset($_SESSION["ss_user_email"]);
        unset($_SESSION["ss_user_password_hash"]);
    }

    public static function createObjectFromSession(?string $name = null): IUser
    {
        return new User(
            $name,
            $_SESSION["ss_user_email"] ?? '',
            $_SESSION["ss_user_password_hash"] ?? '',
        );
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public static function composePasswordHash(string $password): string
    {
        return hash('SHA256', 'a$2' . $password);
    }

    public static function selectByEmailAndPasswordHash(string $email, string $password_hash): ?IUser
    {
        if (self::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objects = $db->singleRequest()
            ->singleQuery((new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(self::getTableName())
                            ->where('email=:email')
                            ->and('password_hash=:password_hash'))
            ->setClassName(self::class)
            ->addParameter(':email', $email)
            ->addParameter(':password_hash', $password_hash)
            ->send()[0] ?? null;
        return $objects instanceof User ? $objects : null;
    }

    public static function selectByEmail(string $email): ?IUser
    {
        if (self::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objects = $db->singleRequest()
            ->singleQuery((new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(self::getTableName())
                            ->where('email=:email'))
            ->setClassName(self::class)
            ->addParameter(':email', $email)
            ->send()[0] ?? null;
        return $objects instanceof User ? $objects : null;
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery((new TableMySQLQueryBuilder())
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
                            ]))
                ->send();
            return true;
        }
        return false;
    }
}
