import SelectElementError from './errors/SelectElementError.js';
import { ServerRequests } from '../../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js';
import { JsonRpcRequestClient } from '../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js';
import { serverRequestModule } from '../MainLibrary/MainLibrary.js';
import { Notification } from '../Notification/Notification.js';

export class SelectElement {
    #selectElement;
    #mainItem;
    #subMenu;
    #serverRequest;
    #notification;
    #moduleName;
    #moduleMethod;

    constructor({ selector, serverRequest, notification, moduleName, moduleMethod }) {
        if (typeof selector !== 'string') {
            throw new SelectElementError('selector must be a string.');
        }

        if (typeof moduleName !== 'string') {
            throw new SelectElementError('moduleName must be a string.');
        }

        if (typeof moduleMethod !== 'string') {
            throw new SelectElementError('moduleMethod must be a string.');
        }

        this.#moduleName = moduleName;
        this.#moduleMethod = moduleMethod;
        this.selectElement = document.querySelector(selector) ?? null;
        this.serverRequest = serverRequest;
        this.notification = notification;
        this.mainItem = this.selectElement.querySelector('.item-selected') ?? null;
        this.subMenu = this.selectElement.querySelector('menu') ?? null;
    }

    /**
     * @param {HTMLElement} value
     */
    set selectElement(value) {
        if (!(value instanceof HTMLElement)) {
            throw new SelectElementError('selectElement element must be a DOM element (HTMLElement).');
        }
        this.#selectElement = value;
    }

    /**
     * @return {HTMLElement}
     */
    get selectElement() {
        if (!(this.#selectElement instanceof HTMLElement)) {
            throw new SelectElementError('selectElement element must be a DOM element (HTMLElement).');
        }

        return this.#selectElement;
    }

    /**
     * @param {HTMLElement} value
     */
    set mainItem(value) {
        if (!(value instanceof HTMLElement)) {
            throw new SelectElementError('mainItem element must be a DOM element (HTMLElement).');
        }
        this.#mainItem = value;
    }

    /**
     * @return {HTMLElement}
     */
    get mainItem() {
        if (!(this.#mainItem instanceof HTMLElement)) {
            throw new SelectElementError('mainItem element must be a DOM element (HTMLElement).');
        }

        return this.#mainItem;
    }

    /**
     * @param {HTMLMenuElement} value
     */
    set subMenu(value) {
        if (!(value instanceof HTMLMenuElement)) {
            throw new SelectElementError('selectElement element must be a DOM element (HTMLMenuElement).');
        }
        this.#subMenu = value;
    }

    /**
     * @return {HTMLMenuElement}
     */
    get subMenu() {
        if (!(this.#subMenu instanceof HTMLMenuElement)) {
            throw new SelectElementError('subMenu element must be a DOM element (HTMLMenuElement).');
        }

        return this.#subMenu;
    }

    /**
     * @param {ServerRequests} value
     */
    set serverRequest(value) {
        if (!(value instanceof ServerRequests)) {
            throw new SelectElementError('value element must be a ServerRequests class.');
        }
        this.#serverRequest = value;
    }

    /**
     * @return {ServerRequests}
     */
    get serverRequest() {
        if (!(this.#serverRequest instanceof ServerRequests)) {
            throw new SelectElementError('#serverRequest must be a ServerRequests class.');
        }

        return this.#serverRequest;
    }

    /**
     * @param {Notification} value
     */
    set notification(value) {
        if (!(value instanceof Notification)) {
            throw new SelectElementError('value значение должно иметь родительский класс Notification.');
        }
        this.#notification = value;
    }

    /**
     * @return {Notification}
     */
    get notification() {
        if (!(this.#notification instanceof Notification)) {
            throw new SelectElementError('#notification поле должно иметь родительский класс Notification.');
        }

        return this.#notification;
    }

    getPageId() {
        let input = this.selectElement.querySelector('data input[name="page_id"]');
        if (input instanceof HTMLInputElement) {
            if (input.hasAttribute('value')) {
                return input.getAttribute('value');
            }
        }

        return -1;
    }

    setPageId(value) {
        if (typeof value === 'number') {
            throw new SelectElementError('value для setPageId должно быть number.');
        }

        let input = this.selectElement.querySelector('data input[name="page_id"]') ?? null;
        if (input instanceof HTMLInputElement) {
            input.setAttribute('value', value);
        }
    }

    actionMainItemServerRequest(eventName) {
        let requestQuery = {
            module_name: this.#moduleName ?? '',
            module_method: this.#moduleMethod ?? '',
        };

        let inputs = this.selectElement.querySelectorAll('input') ?? [];
        inputs.forEach((input) => {
            if (input instanceof HTMLInputElement) {
                let inputName = input.name;
                if (typeof inputName !== 'string' || inputName === '') {
                    throw new SelectElementError('У input должен быть указан name.');
                }

                requestQuery[inputName] = input.value;
            }
        });

        this.mainItem.addEventListener('click', (event) => {
            if (!this.hasActivatedDisplaySync()) {
                return;
            }

            this.clearListSubMenu();

            this.serverRequest.request(eventName, {
                url: 'admin/module',
                method: 'POST',
                objectForDataPacker: requestQuery,
                requestDataPacker: this.processingData,
                responseHandler: (value) => {
                    let res = serverRequestModule(value, '.content-wrapper', this.notification) ?? null;
                    this.addItemsForList(res);
                },
            });
        });
    }

    addItemsForList(data) {
        if (Array.isArray(data)) {
            this.clearListSubMenu();
            let elemAdded = false;
            data.forEach((item) => {
                if (Object.hasOwn(item, 'id') && Object.hasOwn(item, 'title')) {
                    if (elemAdded === false) {
                        this.disableDisplaySync();
                        elemAdded = true;
                    }
                    let elemLi = document.createElement('li');
                    elemLi.setAttribute('value', item.id);
                    elemLi.innerHTML = '<p>[id: ' + item.id + '] ' + item.title + '</p>';
                    elemLi.addEventListener('click', (event) => {
                        this.actionItemForSubMenu(elemLi);
                    });
                    this.subMenu.append(elemLi);
                }
            });
        }
    }

    clearListSubMenu() {
        let items = this.subMenu.querySelectorAll('li:not(.sync)');
        items.forEach((item) => {
            if (item instanceof HTMLElement) {
                item.remove();
            }
        });
    }

    // eslint-disable-next-line class-methods-use-this
    processingData(requestQuery) {
        return JSON.stringify(new JsonRpcRequestClient('response', requestQuery));
    }

    actionMainItem() {
        this.mainItem.addEventListener('click', (event) => {
            if (this.subMenu.style.display !== 'flex') {
                this.subMenu.style.display = 'flex';
                this.activatedDisplaySync();
            } else {
                this.subMenu.style.display = '';
                this.disableDisplaySync();
            }
        });
    }

    actionItemForSubMenu(item) {
        if (!(item instanceof HTMLElement)) {
            throw new SelectElementError('item для actionItemForSubMenu должно быть HTMLElement.');
        }

        if (!item.hasAttribute('value')) {
            throw new SelectElementError('item для actionItemForSubMenu должен иметь атрибут value.');
        }

        let elemP = this.mainItem.querySelector('p') ?? null;
        if (elemP instanceof HTMLElement) {
            elemP.innerText = item.innerText;
            this.setPageId(item.getAttribute('value'));
        }
        this.subMenu.style.display = '';
    }

    hasActivatedDisplaySync() {
        let syncElement = this.subMenu.querySelector('.sync');
        if (syncElement instanceof HTMLElement && syncElement.style.display === 'flex') {
            return true;
        }

        return false;
    }

    activatedDisplaySync() {
        let syncElement = this.subMenu.querySelector('.sync');
        if (syncElement instanceof HTMLElement && syncElement.style.display !== 'flex') {
            syncElement.style.display = 'flex';
        }
    }

    disableDisplaySync() {
        let syncElement = this.subMenu.querySelector('.sync');
        if (syncElement instanceof HTMLElement && syncElement.style.display === 'flex') {
            syncElement.style.display = '';
        }
    }
}
