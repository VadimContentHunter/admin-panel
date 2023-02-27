<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests;

use PDO;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\DB;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\AdminPanel\models\User\User;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class UserTest extends TestCase
{
    protected User $user;

    protected string $path;

    protected function setUp(): void
    {
        $this->user = new User('UserTest', 'userTest@mail.com', 'password1');

        try {
            DB::$connector = new Connector(
                typeDb: 'mysql',
                host: 'localhost',
                user: 'mytest',
                password: 'mytest',
                dbName: 'db_admin_panel',
                options: [
                        PDO::ATTR_PERSISTENT => true
                    ]
            );

            DB::$connector->connect();

            User::dropTable();
            User::createTable();
        } catch (\Exception $e) {
            $this->markTestSkipped(
                'Пропуск теста из за отсутствия базы данных в удаленном окружении.'
            );
        }
    }

    protected function tearDown(): void
    {
        User::dropTable();
    }

    public function test_insertReturnObjectToDb_shouldReturnAnObject(): void
    {
        $temp = $this->user->insertReturnObjectToDb();
        $this->assertInstanceOf(User::class, $temp);
    }

    public function test_validateByEmailAndPassword_shouldReturnTrue(): void
    {
        $this->user->insertReturnObjectToDb();
        $object2 = new User('UserTest2', 'userTest2@mail.com', 'password2');
        $object2->insertObjectToDb();
        $object3 = new User('UserTest3', 'userTest3@mail.com', 'password1');
        $object3->insertObjectToDb();

        $this->assertEquals(true, $object2->validateByEmailAndPassword());
    }

    public function test_validateByEmailAndPassword_shouldReturnFalse(): void
    {
        $this->user->insertReturnObjectToDb();
        $object3 = new User('UserTest3', 'userTest3@mail.com', 'password1');
        $object3->insertObjectToDb();

        $object2 = new User('UserTest2', 'userTest2@mail.com', 'password2');

        $this->assertEquals(false, $object2->validateByEmailAndPassword());
    }
}
