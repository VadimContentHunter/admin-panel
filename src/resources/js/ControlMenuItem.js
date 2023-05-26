import { controlMenuItem, requestDataPackerJson, serverRequestModule } from './MainLibrary/MainLibrary.js';
import { ServerRequests } from '../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js';
import { RequestBase } from '../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestBase.js';
// import { JsonRpcRequestClient } from '../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js';
// import { JsonRpcResponseClient } from '../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js';

const serverRequest = new ServerRequests();
serverRequest.eventRegistration('eventRequestMenuItem', new RequestBase());

controlMenuItem('.sidebar menu > li', '.content-wrapper', (serverData) => {
    serverRequest.request('eventRequestMenuItem', {
        url: 'admin/module',
        method: 'POST',
        objectForDataPacker: serverData,
        requestDataPacker: requestDataPackerJson,
        responseHandler: (value) => {
            serverRequestModule(value, '.content-wrapper');
        },
    });
});
controlMenuItem('header .account-block menu > li', '.content-wrapper');
