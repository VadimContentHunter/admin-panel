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

let { RequestForms } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-server-requests/src/requests/RequestForms.js"
);

let { JsonRpcRequestClient } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js"
);

let { JsonRpcResponseClient } = await import(
    pathNodeModules +
        "/node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js"
);

// import { ServerRequests } from "../../../node_modules/vadimcontenthunter-server-requests/src/ServerRequests.js";
// import { RequestForms } from "../../../node_modules/vadimcontenthunter-server-requests/src/requests/RequestForms.js";
// import { JsonRpcRequestClient } from "../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcRequestClient.js";
// import { JsonRpcResponseClient } from "../../../node_modules/vadimcontenthunter-json-rpc-client/src/JsonRpcResponseClient.js";

const serverRequest = new ServerRequests();
serverRequest.eventRegistration("eventRequestLoginForm", new RequestForms());
// serverRequest.request('eventFetchRequestForm');

const allForms = document.querySelectorAll("form");
allForms.forEach((form) => {
    if (form instanceof HTMLFormElement) {
        const button = form.querySelector('input[type="button"]');
        if (button instanceof HTMLInputElement) {
            button.addEventListener("click", (event) => {
                serverRequest.request("eventRequestLoginForm", {
                    formElem: form,
                    method: "POST",
                    requestDataPacker: (valueObject) => {
                        let result = null;

                        if (
                            form.hasAttribute("json-rpc-method") &&
                            form.getAttribute("json-rpc-method") !== ""
                        ) {
                            // преобразование в чистый объект Так как данные могут иметь вид FormData
                            let params = {};
                            valueObject.forEach((value, key) => {
                                params[key] = value;
                            });

                            const jsonRpcRequestClient =
                                new JsonRpcRequestClient(
                                    form.getAttribute("json-rpc-method"),
                                    params
                                );
                            result = JSON.stringify(jsonRpcRequestClient);
                        }

                        return result;
                    },
                    responseHandler: (value) => {
                        const jsonRpcResponseClient = new JsonRpcResponseClient(
                            value
                        );
                        if (jsonRpcResponseClient.checkError()) {
                            // eslint-disable-next-line no-alert
                            alert(jsonRpcResponseClient.error.message);
                            if (
                                typeof jsonRpcResponseClient.error?.data ===
                                "object"
                            ) {
                                Object.keys(
                                    jsonRpcResponseClient.error.data
                                ).forEach((key) => {
                                    if (
                                        typeof jsonRpcResponseClient.error.data[
                                            key
                                        ] === "string"
                                    ) {
                                        const message =
                                            jsonRpcResponseClient.error.data[
                                                key
                                            ] ?? "";
                                        let elemOutput =
                                            form.querySelector(
                                                'output[name="' + key + '"]'
                                            ) ?? null;
                                        if (elemOutput instanceof HTMLElement) {
                                            elemOutput.innerText = message;
                                            elemOutput.style.display = "block";
                                        }
                                    }
                                });
                            }
                        } else {
                            let result = jsonRpcResponseClient.result;
                            if (typeof result?.redirect === "string") {
                                document.location.href = result.redirect;
                            }

                            if (
                                typeof result === "object" &&
                                typeof result?.alert === "string"
                            ) {
                                // eslint-disable-next-line no-alert
                                alert(result.alert);
                            }
                        }
                    },
                });
            });
        }
    }
});
