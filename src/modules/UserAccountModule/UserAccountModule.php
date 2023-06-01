<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\UserAccountModule;

use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\models\User\User;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcResponse;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\validations\LoginValidate;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\ModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\ContentItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class UserAccountModule extends Module
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi, array $parameters = []): IModule
    {
        $user = null;
        if ($parameters['user'] && $parameters['user'] instanceof IUser) {
            $user = $parameters['user'];
        }

        $content_parameters = [
            'title' => $this->getAlias(),
            'user_name' => ($user?->getName()) ?? '',
            'user_email' => ($user?->getEmail()) ?? '',
        ];

        $contentContainerUi->setTitle($this->getAlias());
        $contentContainerUi->addContent(
            (new ContentItemUi(
                $content_parameters,
                AdminPanelSetting::getPathToTemplatesForModules($this->getName(), '/admin')
            ))
            ->setContent('account-content.php')
            ->setGridColumnCount(3)
        );

        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new ModuleItemUi($this->getAlias(), $this->getName(), valueData: $this->getName() . '|' . 'getContent');
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function getContent(array $parameters): IModuleResponse|null
    {
        $contentContainerUi = $parameters['contentContainerUi'] ?? null;
        if (!($contentContainerUi instanceof IContentContainerUi)) {
            return null;
        }

        $this->builderAdminContentUi($contentContainerUi, $parameters);
        return (new ModuleResponse($parameters['request_id'] ?? null))
            ->setResponseHtmlAndJsFromFiles($contentContainerUi->getHtml(), [
                AdminPanelSetting::getPathModuleUrl($this->getName()) . '/js/admin/EditUserData.js',
                AdminPanelSetting::getPathModuleUrl($this->getName()) . '/js/admin/SaveNewForm.js',
            ]);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function updateUserAccount(array $parameters): IModuleResponse|null
    {
        $user_name = $parameters['name'] ?? '';
        $user_email = $parameters['email'] ?? '';
        $user_new_email = $parameters['new_email'] ?? '';
        $user_current_password = $parameters['current_password'] ?? '';
        $user_new_password = $parameters['new_password'] ?? '';
        $user_confirm_new_password = $parameters['confirm_new_password'] ?? '';

        if ($user_new_email === '') {
            $user_new_email = $user_email;
        }

        $moduleResponse = new ModuleResponse($parameters['request_id'] ?? null);
        $userLoginValidate = new LoginValidate();
        if ($userLoginValidate->isNameEmpty($user_name)) {
            return (new ModuleResponse($parameters['request_id'] ?? null))
                    ->setResponseError(new JsonRpcError(101, 'Имя не указано.'));
        }

        if ($userLoginValidate->isEmailEmpty($user_email)) {
            return $moduleResponse->setResponseError(new JsonRpcError(102, 'Email не указан.'));
        }

        if ($userLoginValidate->isPasswordEmpty($user_current_password)) {
            return $moduleResponse->setResponseError(new JsonRpcError(103, 'Пароль не указан.'));
        }

        if ($userLoginValidate->isPasswordEmpty($user_new_password)) {
            return $moduleResponse->setResponseError(new JsonRpcError(103, 'Новый пароль не указан.'));
        }

        if ($userLoginValidate->checkPasswordMatchWithConfirmPassword($user_new_password, $user_confirm_new_password)) {
            return $moduleResponse->setResponseError(new JsonRpcError(104, 'Повторите повторите новый пароль! Он не совпадает с веденным.'));
        }

        $user = User::selectByEmailAndPasswordHash($user_email, User::composePasswordHash($user_current_password));
        if ($user instanceof User) {
            if ($userLoginValidate->hasValidating()) {
                try {
                    $user->setName($user_name);
                    $user->setEmail($user_new_email);
                    $user->setPasswordHash(User::composePasswordHash($user_new_password));
                    $user->updateObjectToDbById();
                    return $moduleResponse->setResponseNotification('Данные успешно обновлены.');
                } catch (AdminPanelException $errAdminPanel) {
                    return $moduleResponse->setResponseError(new JsonRpcError(106, 'Не удалось обновить данные для аккаунта.'));
                }
            } else {
                return $moduleResponse->setResponseError(new JsonRpcError(105, 'Неизвестная ошибка при валидации.'));
            }
        } else {
            return $moduleResponse->setResponseError(new JsonRpcError(100, 'Неправильный email или пароль!'));
        }
    }
}
