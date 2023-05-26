// import MainLibraryError from './errors/MainLibraryError.js';
import { JsonRpcRequestClient } from '../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js';
import SetClickHandlerOnElemError from './errors/SetClickHandlerOnElemError.js';
import ControlMenuItemError from './errors/ControlMenuItemError.js';
import SidebarUpdateError from './errors/SidebarUpdateError.js';
import SetContentError from './errors/SetContentError.js';

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

export function requestDataPackerJson(valueObject) {
    const jsonRpcRequestClient = new JsonRpcRequestClient('response', {
        module_name: valueObject?.moduleName ?? '',
        module_method: valueObject?.method ?? '',
    });
    return JSON.stringify(jsonRpcRequestClient);
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

                    serverRequest({
                        url: elemData.getAttribute('value'),
                        moduleName: elemData.getAttribute('value'),
                        container: selectorContainer,
                    });

                    if (!checkActivatedClass) {
                        item.classList.add('activated');
                    }
                }
            });
        }
    });
}
