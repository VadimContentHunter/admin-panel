import {
    setClickHandlerOnElem,
    requestDataPackerJson,
    serverRequestModule,
    eventDelete,
} from '../../../src/resources/js/MainLibrary/MainLibrary.js';
import { Notification } from '../../../src/resources/js/Notification/Notification.js';
import { ServerRequests } from '../../../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js';
import { RequestBase } from '../../../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestBase.js';
import { EditStatus } from '../../../src/resources/js/ActionEdit/ActionEdit.js';

const notificationUserAccountModule = new Notification(
    '.account-control > .notification-block',
    '.sub-menu',
    '.notifications',
);

const serverRequestUserAccountModule = new ServerRequests();
const requestBase = new RequestBase();
serverRequestUserAccountModule.eventRegistration('eventSaveUserAccountModule', requestBase);
eventDelete(document, 'eventSaveUserAccountModule', requestBase);

setClickHandlerOnElem('.container-one form .panel-footer button', () => {
    EditStatus('.container-one form .panel-footer', 'form', false);
    serverRequestUserAccountModule.request('eventSaveUserAccountModule', {
        url: 'admin/module',
        method: 'POST',
        objectForDataPacker: {
            moduleName: 'UserAccountModule',
            moduleMethod: 'updateUserAccount',
        },
        requestDataPacker: requestDataPackerJson,
        responseHandler: (value) => {
            serverRequestModule(value, '.content-wrapper', notificationUserAccountModule);
        },
    });
});
