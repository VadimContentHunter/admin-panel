import { controlMenuItem, requestDataPackerJson } from './MainLibrary/MainLibrary.js';
import { ServerRequests } from '../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js';
import { RequestBase } from '../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestBase.js';
// import { JsonRpcRequestClient } from '../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js';
import { JsonRpcResponseClient } from '../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js';

const serverRequest = new ServerRequests();
serverRequest.eventRegistration('eventRequestMenuItem', new RequestBase());

controlMenuItem('.sidebar menu > li', '.content-wrapper', (serverData) => {
    serverRequest.request('eventRequestMenuItem', {
        url: 'admin/module',
        method: 'POST',
        objectForDataPacker: serverData,
        requestDataPacker: requestDataPackerJson,
        responseHandler: (value) => {
            const jsonRpcResponseClient = new JsonRpcResponseClient(value);
            if (jsonRpcResponseClient.checkError()) {
                // eslint-disable-next-line no-alert
                alert(jsonRpcResponseClient.error.message);
            } else {
                // let result = jsonRpcResponseClient.result;
            }
        },
    });
});
controlMenuItem('header .account-block menu > li', '.content-wrapper');
