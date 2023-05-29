// import MainLibraryError from './errors/MainLibraryError.js';
import { JsonRpcRequestClient } from '../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js';
import { JsonRpcResponseClient } from '../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js';
import SetClickHandlerOnElemError from './errors/SetClickHandlerOnElemError.js';
import ControlMenuItemError from './errors/ControlMenuItemError.js';
import SidebarUpdateError from './errors/SidebarUpdateError.js';
import SetContentError from './errors/SetContentError.js';
import ServerRequestModuleError from './errors/ServerRequestModuleError.js';
import CreatedScriptBlockError from './errors/CreatedScriptBlockError.js';
import { Notification } from '../Notification/Notification.js';

export function setClickHandlerOnElem(selector, clickHandler) {
    if (typeof selector !== 'string') {
        throw new SetClickHandlerOnElemError('Тип параметра selector, должен быть "string"');
    }

    if (typeof clickHandler !== 'function') {
        throw new SetClickHandlerOnElemError('Тип параметра clickHandler, должен быть "function"');
    }

    let elem = document.querySelector(selector) ?? null;
    if (elem === null) {
        throw new SetClickHandlerOnElemError('Параметра selector, не найден.');
    }

    elem.addEventListener('click', (e) => {
        e.preventDefault();
        clickHandler(elem);
    });
}

export function sidebarUpdate(selector) {
    if (typeof selector !== 'string') {
        throw new SidebarUpdateError('Тип параметра selector, должен быть "string"');
    }

    let sidebar = document.querySelector(selector) ?? null;
    if (sidebar === null) {
        throw new SidebarUpdateError('Selector not found!');
    }

    let items = sidebar.querySelectorAll('menu > li.activated');
    items.forEach((item) => {
        item.classList.remove('activated');
    });
}

export function setContent(selector, content) {
    if (typeof selector !== 'string') {
        throw new SetContentError('Тип параметра selector, должен быть "string"');
    }

    if (typeof content !== 'string') {
        throw new SetContentError('Тип параметра content, должен быть "string"');
    }

    let container = document.querySelector(selector) ?? null;
    if (container === null) {
        throw new SetContentError('Selector not found!');
    }

    container.innerHTML = content;
}

export function createdScriptBlock(pathToJsFile) {
    if (typeof pathToJsFile !== 'string') {
        throw new CreatedScriptBlockError('Тип параметра pathToJsFile, должен быть "string"');
    }

    let script = document.createElement('script');
    script.src = pathToJsFile;
    script.type = 'module';
    // script.onload = () => {};
    script.onerror = () => {
        console.log('Error occurred while loading script');
    };

    document.body.append(script);
}

/**
 * @param {string} selector
 * @param {string} selectorContainer
 * @param {function({url:string, moduleName:string, setContent:string})} serverRequest
 * @param {boolean} checkActivatedClass
 */
export function controlMenuItem(selector, selectorContainer, serverRequest, checkActivatedClass = true) {
    if (typeof selector !== 'string') {
        throw new ControlMenuItemError('Тип параметра selector, должен быть "string"');
    }

    if (typeof selectorContainer !== 'string') {
        throw new ControlMenuItemError('Тип параметра selectorContainer, должен быть "string"');
    }

    if (typeof checkActivatedClass !== 'boolean') {
        throw new ControlMenuItemError('Тип параметра selectorContainer, должен быть "boolean"');
    }

    let items = document.querySelectorAll(selector) ?? null;
    if (items === null) {
        throw new ControlMenuItemError('Selector not found!');
    }

    items.forEach((item) => {
        let elemData = item.querySelector('data') ?? null;
        if (elemData === null) {
            throw new ControlMenuItemError('Selector for element "data" not found!');
        }

        if (elemData.hasAttribute('value')) {
            item.addEventListener('click', (e) => {
                if (!item.classList.contains('activated') || !checkActivatedClass) {
                    e.preventDefault();
                    sidebarUpdate('.sidebar');
                    setContent(selectorContainer, '');

                    let valuesElemData = elemData.getAttribute('value').split('|');

                    serverRequest({
                        moduleName: valuesElemData[0] ?? '',
                        moduleMethod: valuesElemData[1] ?? '',
                    });

                    if (!checkActivatedClass) {
                        item.classList.add('activated');
                    }
                }
            });
        }
    });
}

export function requestDataPackerJson(valueObject) {
    const jsonRpcRequestClient = new JsonRpcRequestClient('response', {
        module_name: valueObject?.moduleName ?? '',
        module_method: valueObject?.moduleMethod ?? '',
    });
    return JSON.stringify(jsonRpcRequestClient);
}

export function serverRequestModule(value, selectorContainer, notification) {
    if (typeof selectorContainer !== 'string') {
        throw new ServerRequestModuleError('Тип параметра selectorContainer, должен быть "string"');
    }

    if (!(notification instanceof Notification)) {
        throw new ServerRequestModuleError('Тип параметра notification, должен принадлежать класс "Notification"');
    }

    const jsonRpcResponseClient = new JsonRpcResponseClient(value);
    if (jsonRpcResponseClient.checkError()) {
        // eslint-disable-next-line no-alert
        // alert(jsonRpcResponseClient.error.message);
        notification.addNotification('Ошибка', new Date().toLocaleString(), jsonRpcResponseClient.error.message);
        notification.setStatusNew();
        notification.update();
    } else {
        if (typeof jsonRpcResponseClient.result === 'string') {
            setContent(selectorContainer, jsonRpcResponseClient.result);
        }

        if (
            typeof jsonRpcResponseClient.result === 'object' &&
            typeof jsonRpcResponseClient.result?.location === 'string'
        ) {
            document.location.href = jsonRpcResponseClient.result.location;
        }

        if (
            typeof jsonRpcResponseClient.result === 'object' &&
            typeof jsonRpcResponseClient.result?.html === 'string'
        ) {
            setContent(selectorContainer, jsonRpcResponseClient.result.html);
        }

        if (
            typeof jsonRpcResponseClient.result === 'object' &&
            typeof jsonRpcResponseClient.result?.pathJsFile === 'string'
        ) {
            createdScriptBlock(jsonRpcResponseClient.result.pathJsFile);
        }
        // let result = jsonRpcResponseClient.result;
    }
}
