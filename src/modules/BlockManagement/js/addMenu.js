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

let { eventDelete } = await import(
    pathResourcesJs + "/MainLibrary/MainLibrary.js"
);

let { SelectElement } = await import(
    pathResourcesJs + "/SelectElement/SelectElement.js"
);

// import { SelectElement } from '../../../../src/resources/js/SelectElement/SelectElement.js';
// import { ServerRequests } from '../../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js';
// import { RequestBase } from '../../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestBase.js';
// import { Notification } from '../../../../src/resources/js/Notification/Notification.js';
// import { eventDelete } from '../../../../src/resources/js/MainLibrary/MainLibrary.js';

const notificationSelect = new Notification('.account-control > .notification-block', '.sub-menu', '.notifications');
const serverRequestSelect = new ServerRequests();

const requestBaseSelect = new RequestBase();
eventDelete(document, 'eventRequestSelect', requestBaseSelect);
serverRequestSelect.eventRegistration('eventRequestSelect', requestBaseSelect);

const selectPageMenu = new SelectElement({
    selector: '#select_menu',
    serverRequest: serverRequestSelect,
    notification: notificationSelect,
    moduleName: 'BlockManagement',
    moduleMethod: 'getPages',
});

selectPageMenu.actionMainItem();
selectPageMenu.actionMainItemServerRequest('eventRequestSelect');
