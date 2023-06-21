<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel;

use PDO;
use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\controllers\MainController;
use vadimcontenthunter\AdminPanel\controllers\UserController;
use vadimcontenthunter\AdminPanel\controllers\ModuleController;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\controllers\AuthorizationController;
use vadimcontenthunter\AdminPanel\controllers\ModuleResponseController;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DatabaseMySQLQueryBuilder\DatabaseMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AdminPanelAPI
{
    protected Routing $routing;

    /**
     * @var IModule[]
     */
    protected array $modules = [];

    /**
     * @var mixed[]
     */
    protected array $parameters = [];

    public function __construct(bool $autoConnect = true)
    {
        if ($autoConnect) {
            $this->createConnectToDb();
        }

        $this->routing = new Routing();
        $this->routing->addRoute('~^admin$~', MainController::class, 'view');
        $this->routing->addRoute('~^admin/login$~', AuthorizationController::class, 'view');
        $this->routing->addRoute('~^admin/users$~', UserController::class);
        $this->routing->addRoute('~^admin/module$~', ModuleResponseController::class);
        $this->routing->addRoute(
            '~^admin/module/rest/(?<execution_method>\w+)/(?<module_name>\w+)(?<module_url_data>/[\w\\/-]+)?(?<module_parameters>(\?|&)[\w&=%.,]+)?$~',
            ModuleController::class
        );
        // $this->routing->addRoute('~^admin/GET/users$~', UserController::class, 'loginUser');
        // $this->routing->addRoute('~^admin/POST/users$~', UserController::class, 'registerUser');
        // $this->routing->addRoute('~^admin/module/(?<module_name>\w+)/~', ModuleResponseController::class, 'response');
    }

    public function createConnectToDb(): AdminPanelAPI
    {
        DB::$connector = new Connector(
            typeDb: AdminPanelSetting::$dbType,
            host: AdminPanelSetting::$dbHost,
            user: AdminPanelSetting::$dbUser,
            password: AdminPanelSetting::$dbPassword,
            // dbName: AdminPanelSetting::$dbName,
            options: [
                    PDO::ATTR_PERSISTENT => true
            ]
        );

        try {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery(
                    (new DatabaseMySQLQueryBuilder())
                        ->createDatabase(AdminPanelSetting::$dbName)
                )
                ->send();
        } catch (\Exception $e) {
        } finally {
            DB::$connector = new Connector(
                typeDb: AdminPanelSetting::$dbType,
                host: AdminPanelSetting::$dbHost,
                user: AdminPanelSetting::$dbUser,
                password: AdminPanelSetting::$dbPassword,
                dbName: AdminPanelSetting::$dbName,
                options: [
                        PDO::ATTR_PERSISTENT => true
                ]
            );
        }

        return $this;
    }

    public function getRouting(): Routing
    {
        return $this->routing;
    }

    public function addModule(Module $module): AdminPanelAPI
    {
        $this->modules[] = $module->initializeReplaceThisObject();

        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function start(string $current_url, array $parameters = []): void
    {
        $this->parameters += $parameters;
        $this->parameters['modules'] = $this->modules;
        $this->routing->start($current_url, $this->parameters);
    }
}
