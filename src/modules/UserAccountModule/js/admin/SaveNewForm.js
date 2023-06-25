if (typeof pathNodeModules === "undefined") {
    throw Error("Ошибка укажите переменную pathNodeModules");
}

if (typeof pathResourcesJs === "undefined") {
    throw Error("Ошибка укажите переменную pathResourcesJs");
}

if (typeof pathModule === "undefined") {
    throw Error("Ошибка укажите переменную pathModule");
}

let { ServerRequests } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js"
);

let { RequestForms } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-server-requests/src/requests/RequestForms.js"
);

let { Notification } = await import(
    pathResourcesJs + "/Notification/Notification.js"
);

let { serverRequestModule, eventDelete } = await import(
    pathResourcesJs + "/MainLibrary/MainLibrary.js"
);

let { JsonRpcRequestClient } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js"
);

let { EditStatus } = await import(
    pathModule + "/UserAccountModule/js/admin/ActionEdit/ActionEdit.js"
);

// import {
//     serverRequestModule,
//     eventDelete,
// } from "../../../src/resources/js/MainLibrary/MainLibrary.js";
// import { Notification } from "../../../src/resources/js/Notification/Notification.js";
// import { ServerRequests } from "../../../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js";
// import { RequestForms } from "../../../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestForms.js";
// import { JsonRpcRequestClient } from "../../../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js";
// import { EditStatus } from "../../../../../src/modules/UserAccountModule/js/admin/ActionEdit/ActionEdit.js";

const notificationUserAccountModule = new Notification(
    ".account-control > .notification-block",
    ".sub-menu",
    ".notifications"
);

const serverRequestUserAccountModule = new ServerRequests();
const requestForms = new RequestForms();
serverRequestUserAccountModule.eventRegistration(
    "eventSaveUserAccountModule",
    requestForms
);
eventDelete(document, "eventSaveUserAccountModule", requestForms);

const form = document.querySelector("form");
if (form instanceof HTMLFormElement) {
    const button = form.querySelector('input[type="button"]');
    if (button instanceof HTMLInputElement) {
        button.addEventListener("click", (event) => {
            EditStatus(".container-one form .panel-footer", "form", false);
            serverRequestUserAccountModule.request(
                "eventSaveUserAccountModule",
                {
                    formElem: form,
                    url: "admin/module",
                    method: "POST",
                    requestDataPacker: (valueObject) => {
                        let params = {};
                        valueObject.forEach((value, key) => {
                            params[key] = value;
                        });
                        params.module_name = "UserAccountModule";
                        params.module_method = "updateUserAccount";
                        const jsonRpcRequestClient = new JsonRpcRequestClient(
                            "response",
                            params
                        );
                        return JSON.stringify(jsonRpcRequestClient);
                    },
                    responseHandler: (value) => {
                        serverRequestModule(
                            value,
                            ".content-wrapper",
                            notificationUserAccountModule
                        );
                    },
                }
            );
        });
    }
}
