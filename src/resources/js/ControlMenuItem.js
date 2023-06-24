if (typeof pathNodeModules === "undefined") {
    throw Error("Ошибка укажите переменную pathNodeModules");
}

if (typeof pathResourcesJs === "undefined") {
    throw Error("Ошибка укажите переменную pathResourcesJs");
}

let { ServerRequests } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js"
);

let { RequestBase } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-server-requests/src/requests/RequestBase.js"
);

let { Notification } = await import(
    pathResourcesJs + "/Notification/Notification.js"
);

let { controlMenuItem, requestDataPackerJson, serverRequestModule } =
    await import(pathResourcesJs + "/MainLibrary/MainLibrary.js");

// import {
//     controlMenuItem,
//     requestDataPackerJson,
//     serverRequestModule,
// } from "./MainLibrary/MainLibrary.js";
// import { ServerRequests } from "../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js";
// import { RequestBase } from "../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestBase.js";
// import { Notification } from "./Notification/Notification.js";

const notification = new Notification(
    ".account-control > .notification-block",
    ".sub-menu",
    ".notifications"
);

const serverRequest = new ServerRequests();
serverRequest.eventRegistration("eventRequestMenuItem", new RequestBase());

controlMenuItem(".sidebar menu > li", ".content-wrapper", (serverData) => {
    serverRequest.request("eventRequestMenuItem", {
        url: "admin/module",
        method: "POST",
        objectForDataPacker: serverData,
        requestDataPacker: requestDataPackerJson,
        responseHandler: (value) => {
            serverRequestModule(value, ".content-wrapper", notification);
        },
    });
});

controlMenuItem(
    "header .account-block menu > li",
    ".content-wrapper",
    (serverData) => {
        serverRequest.request("eventRequestMenuItem", {
            url: "admin/module",
            method: "POST",
            objectForDataPacker: serverData,
            requestDataPacker: requestDataPackerJson,
            responseHandler: (value) => {
                serverRequestModule(value, ".content-wrapper", notification);
            },
        });
    }
);
