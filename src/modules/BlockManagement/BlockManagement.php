<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement;

use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Page\Page;
use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\views\BaseView;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\ModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\view\BlockItemUi;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\view\ListBlocksUi;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Block;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\view\ContentBlocksUi;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\BlockLevel\BlockLevel;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BlockManagement extends Module
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi, array $parameters = []): IModule
    {
        $content = new ContentBlocksUi(
            $this->getAlias(),
            AdminPanelSetting::getPathToTemplatesForModules($this->getName()),
            'admin/main.php'
        );
        // $content->addBlock(
        //     (new BlockItemUi('admin/block-controller.php'))
        //         ->addInput('label', 'text', 'block1', 'block1 selected')
        // );
        // $blocks = Block::selectAllFields();
        // if (is_array($blocks)) {
        //     foreach ($blocks as $key => $block_item) {
        //         if ($block_item instanceof Block) {
        //             $content->addBlock(
        //                 (new BlockItemUi('admin/block-controller.php'))
        //                     ->addInput('label', 'text', 'block1', 'block1 selected')
        //             );
        //         }
        //     }
        // }

        $contentContainerUi->setClassForContainer('fill-container');
        $contentContainerUi->setTitle($this->getAlias());
        $contentContainerUi->setTemplate(AdminPanelSetting::getPathToTemplatesForModules($this->getName()));
        $contentContainerUi->addContent($content);

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
                AdminPanelSetting::getPathModuleUrl($this->getName()) . '/js/IframeController.js',
                AdminPanelSetting::getPathModuleUrl($this->getName()) . '/js/addMenu.js',
            ]);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function getPages(array $parameters): IModuleResponse|null
    {
        return  (new ModuleResponse($parameters['request_id'] ?? null))
                    ->setResponseData([
                        [
                            'id' => 1,
                            'title' => 'Page 1'
                        ],
                        [
                            'id' => 2,
                            'title' => 'Главная страница'
                        ],
                        [
                            'id' => 4,
                            'title' => 'О нас'
                        ],
                    ]);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function viewPageBlock(array $parameters): string
    {
        $baseView = new BaseView(
            substr(AdminPanelSetting::getPathToTemplatesForModules($this->getName()), 0, -1)
        );

        extract($parameters);
        return $baseView->getPage('admin/preview-block.php', [
            'body' => $baseView->getPage('blocks/' . $path_to_block ?? '', [
                $parameters
            ]),
            'css_paths' => [
                AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'),
                AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'),
                AdminPanelSetting::getPathModuleUrl($this->getName()) . '/css/style.css',
            ]
        ]);
        // extract($parameters);
        // $body = $parameters['path_to_block'] ?? '';
        // $blockManagementView = new BlockManagementView();
        // $blockManagementView->viewBlock(AdminPanelSetting::getPathToTemplatesForModules(
        //     $this->getName(),
        //     '/templates/blocks/admin/preview-block.php'
        // ));
    }
}
