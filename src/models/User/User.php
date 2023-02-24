<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\model\User;

use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\model\User\interfaces\IUser;
use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class User extends ActiveRecord implements IUser
{
    public function __construct(
        protected int $id,
        protected ?string $name = null,
        protected ?string $email = null,
        protected ?string $password = null,
    ) {
    }

    public function setId(int $id): IUser
    {
        return $this;
    }

    public function setName(string $name): IUser
    {
        return $this;
    }

    public function setEmail(string $email): IUser
    {
        return $this;
    }

    public function setPassword(string $password): IUser
    {
        return $this;
    }

    public function setPasswordHash(): IUser
    {
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function validateByNameAndPassword(): bool
    {
        return false;
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
                        ->from(static::getTableName())
                            ->where('email=:email')
                            ->and('password=:password')
            )
            ->setClassName(static::class)
            ->addParameter(':email', $email)
            ->addParameter(':password', $password)
            ->send()[0] ?? null;

        return $objects instanceof User ? $objects : null;
    }

    public function getTableName(): string
    {
        return 'users';
    }
}
