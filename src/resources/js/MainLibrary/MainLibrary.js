if (typeof pathNodeModules === "undefined") {
    throw Error("Ошибка укажите переменную pathNodeModules");
}

if (typeof pathResourcesJs === "undefined") {
    throw Error("Ошибка укажите переменную pathResourcesJs");
}

let SetClickHandlerOnElemError = (
    await import(
        pathResourcesJs + "/MainLibrary/errors/SetClickHandlerOnElemError.js"
    )
).default;

let ControlMenuItemError = (
    await import(
        pathResourcesJs + "/MainLibrary/errors/ControlMenuItemError.js"
    )
).default;

let SidebarUpdateError = (
    await import(pathResourcesJs + "/MainLibrary/errors/SidebarUpdateError.js")
).default;

let SetContentError = (
    await import(pathResourcesJs + "/MainLibrary/errors/SetContentError.js")
).default;

let ServerRequestModuleError = (
    await import(
        pathResourcesJs + "/MainLibrary/errors/ServerRequestModuleError.js"
    )
).default;

let CreatedScriptBlockError = (
    await import(
        pathResourcesJs + "/MainLibrary/errors/CreatedScriptBlockError.js"
    )
).default;

let { JsonRpcRequestClient } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js"
);

let { JsonRpcResponseClient } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js"
);

let { Notification } = await import(
    pathResourcesJs + "/Notification/Notification.js"
);

// import { JsonRpcRequestClient } from "../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js";
// import { JsonRpcResponseClient } from "../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js";
// import SetClickHandlerOnElemError from "./errors/SetClickHandlerOnElemError.js";
// import ControlMenuItemError from "./errors/ControlMenuItemError.js";
// import SidebarUpdateError from "./errors/SidebarUpdateError.js";
// import SetContentError from "./errors/SetContentError.js";
// import ServerRequestModuleError from "./errors/ServerRequestModuleError.js";
// import CreatedScriptBlockError from "./errors/CreatedScriptBlockError.js";
// import { Notification } from "../Notification/Notification.js";

export function setClickHandlerOnElem(selector, clickHandler) {
    if (typeof selector !== "string") {
        throw new SetClickHandlerOnElemError(
            'Тип параметра selector, должен быть "string"'
        );
    }

    if (typeof clickHandler !== "function") {
        throw new SetClickHandlerOnElemError(
            'Тип параметра clickHandler, должен быть "function"'
        );
    }

    let elem = document.querySelector(selector) ?? null;
    if (elem === null) {
        throw new SetClickHandlerOnElemError("Параметра selector, не найден.");
    }

    elem.addEventListener("click", (e) => {
        e.preventDefault();
        clickHandler(elem);
    });
}

export function eventDelete(element, eventName, eventFunction) {
    if (typeof eventName !== "string") {
        throw new SetClickHandlerOnElemError(
            'Тип параметра eventName, должен быть "string"'
        );
    }

    let deleteFunctionEvent = () => {
        element.removeEventListener(eventName, eventFunction);
        document.removeEventListener(
            "eventDeleteMLibrary",
            deleteFunctionEvent
        );
    };

    document.addEventListener("eventDeleteMLibrary", deleteFunctionEvent);
}

export function sidebarUpdate(selector) {
    if (typeof selector !== "string") {
        throw new SidebarUpdateError(
            'Тип параметра selector, должен быть "string"'
        );
    }

    let sidebar = document.querySelector(selector) ?? null;
    if (sidebar === null) {
        throw new SidebarUpdateError("Selector not found!");
    }

    let items = sidebar.querySelectorAll("menu > li.activated");
    items.forEach((item) => {
        item.classList.remove("activated");
    });
}

export function setContent(selector, content) {
    if (typeof selector !== "string") {
        throw new SetContentError(
            'Тип параметра selector, должен быть "string"'
        );
    }

    if (typeof content !== "string") {
        throw new SetContentError(
            'Тип параметра content, должен быть "string"'
        );
    }

    let container = document.querySelector(selector) ?? null;
    if (container === null) {
        throw new SetContentError("Selector not found!");
    }

    container.innerHTML = content;

    document.dispatchEvent(new Event("eventDeleteScriptBlockForModule"));
    document.dispatchEvent(new Event("eventDeleteMLibrary"));
}

export function createdScriptBlock(pathToJsFile) {
    if (typeof pathToJsFile !== "string") {
        throw new CreatedScriptBlockError(
            'Тип параметра pathToJsFile, должен быть "string"'
        );
    }

    // let search = 'script[src="' + pathToJsFile + '"]';
    // let elemScriptBlock = document.querySelector(search) ?? null;
    // if (elemScriptBlock !== null) {
    //     return;
    // }

    let script = document.createElement("script");
    script.src = pathToJsFile + `?cache=${Math.random()}`;
    script.type = "module";
    // script.setAttribute('nonce', Math.random().toString(36).substring(2));
    script.onerror = () => {
        console.log("Error occurred while loading script");
    };

    let scriptRemove = () => {
        script.remove();
    };
    document.addEventListener("eventDeleteScriptBlockForModule", scriptRemove);
    eventDelete(document, "eventDeleteScriptBlockForModule", scriptRemove);

    document.body.append(script);
}

export function createdScriptBlockFromBody(body) {
    if (typeof body !== "string") {
        throw new CreatedScriptBlockError(
            'Тип параметра body, должен быть "string"'
        );
    }

    let script = document.createElement("script");
    script.innerHTML = body;
    script.type = "module";
    // script.setAttribute('nonce', Math.random().toString(36).substring(2));
    script.onerror = () => {
        console.log("Error occurred while loading script");
    };

    let scriptRemove = () => {
        script.remove();
    };
    document.addEventListener("eventDeleteScriptBlockForModule", scriptRemove);
    eventDelete(document, "eventDeleteScriptBlockForModule", scriptRemove);

    document.body.append(script);
}

/**
 * @param {string} selector
 * @param {string} selectorContainer
 * @param {function({url:string, moduleName:string, setContent:string})} serverRequest
 * @param {boolean} checkActivatedClass
 */
export function controlMenuItem(
    selector,
    selectorContainer,
    serverRequest,
    checkActivatedClass = true
) {
    if (typeof selector !== "string") {
        throw new ControlMenuItemError(
            'Тип параметра selector, должен быть "string"'
        );
    }

    if (typeof selectorContainer !== "string") {
        throw new ControlMenuItemError(
            'Тип параметра selectorContainer, должен быть "string"'
        );
    }

    if (typeof checkActivatedClass !== "boolean") {
        throw new ControlMenuItemError(
            'Тип параметра selectorContainer, должен быть "boolean"'
        );
    }

    let items = document.querySelectorAll(selector) ?? null;
    if (items === null) {
        throw new ControlMenuItemError("Selector not found!");
    }

    items.forEach((item) => {
        let elemData = item.querySelector("data") ?? null;
        if (elemData === null) {
            throw new ControlMenuItemError(
                'Selector for element "data" not found!'
            );
        }

        if (elemData.hasAttribute("value")) {
            item.addEventListener("click", (e) => {
                if (
                    !item.classList.contains("activated") ||
                    !checkActivatedClass
                ) {
                    e.preventDefault();
                    sidebarUpdate(".sidebar");
                    setContent(selectorContainer, "");

                    let valuesElemData = elemData
                        .getAttribute("value")
                        .split("|");

                    serverRequest({
                        moduleName: valuesElemData[0] ?? "",
                        moduleMethod: valuesElemData[1] ?? "",
                    });

                    if (!checkActivatedClass) {
                        item.classList.add("activated");
                    }
                }
            });
        }
    });
}

export function requestDataPackerJson(valueObject) {
    const jsonRpcRequestClient = new JsonRpcRequestClient("response", {
        module_name: valueObject?.moduleName ?? "",
        module_method: valueObject?.moduleMethod ?? "",
    });
    return JSON.stringify(jsonRpcRequestClient);
}

export function serverRequestModule(value, selectorContainer, notification) {
    if (typeof selectorContainer !== "string") {
        throw new ServerRequestModuleError(
            'Тип параметра selectorContainer, должен быть "string"'
        );
    }

    if (!(notification instanceof Notification)) {
        throw new ServerRequestModuleError(
            'Тип параметра notification, должен принадлежать класс "Notification"'
        );
    }

    const jsonRpcResponseClient = new JsonRpcResponseClient(value);
    if (jsonRpcResponseClient.checkError()) {
        // eslint-disable-next-line no-alert
        // alert(jsonRpcResponseClient.error.message);
        notification.addNotification(
            "Ошибка",
            new Date().toLocaleString(),
            jsonRpcResponseClient.error.message
        );
        notification.setStatusNew();
        notification.update();
    } else {
        if (typeof jsonRpcResponseClient.result === "string") {
            setContent(selectorContainer, jsonRpcResponseClient.result);
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            typeof jsonRpcResponseClient.result?.location === "string"
        ) {
            document.location.href = jsonRpcResponseClient.result.location;
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            typeof jsonRpcResponseClient.result?.html === "string"
        ) {
            setContent(selectorContainer, jsonRpcResponseClient.result.html);
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            Array.isArray(jsonRpcResponseClient.result?.pathJsFiles)
        ) {
            jsonRpcResponseClient.result.pathJsFiles.forEach((path) => {
                createdScriptBlock(path);
            });
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            Array.isArray(jsonRpcResponseClient.result?.js)
        ) {
            jsonRpcResponseClient.result.js.forEach((jsBody) => {
                createdScriptBlockFromBody(jsBody);
            });
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            typeof jsonRpcResponseClient.result?.alert === "string"
        ) {
            // eslint-disable-next-line no-alert
            alert(jsonRpcResponseClient.result.alert);
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            typeof jsonRpcResponseClient.result?.notification === "object" &&
            typeof jsonRpcResponseClient.result?.notification?.title ===
                "string" &&
            typeof jsonRpcResponseClient.result?.notification?.data_time ===
                "string" &&
            typeof jsonRpcResponseClient.result?.notification?.content ===
                "string"
        ) {
            notification.addNotification(
                jsonRpcResponseClient.result.notification.title,
                jsonRpcResponseClient.result.notification.data_time,
                jsonRpcResponseClient.result.notification.content
            );
            notification.setStatusNew();
            notification.update();
        }

        if (
            typeof jsonRpcResponseClient.result === "object" &&
            Array.isArray(jsonRpcResponseClient.result?.responseData)
        ) {
            return jsonRpcResponseClient.result?.responseData;
        }
    }

    return null;
}
