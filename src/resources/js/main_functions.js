"use strict";
//
// Проверки
//

function checkString(parameter, parameter_name = '', function_name = '') {
    if ( typeof parameter !== "string") {
        console.error('Error serverRequest > ' + function_name + ' : parameter "' + parameter_name + '" is not a string.');
        return false;
    }
    return true;
}

function checkBoolean(parameter, parameter_name = '', function_name = '') {
    if ( typeof parameter !== "boolean") {
        console.error('Error serverRequest > ' + function_name + ' : parameter "' + parameter_name + '" is not a boolean.');
        return false;
    }
    return true;
}

function checkObject(parameter, parameter_name = '', function_name = '') {
    if ( typeof parameter !== "object") {
        console.error('Error serverRequest > ' + function_name + ' : parameter "' + parameter_name + '" is not a object.');
        return false;
    }
    return true;
}

function checkFunction(parameter, parameter_name = '', function_name = '') {
    if ( typeof parameter !== "function") {
        console.error('Error serverRequest > ' + function_name + ' : parameter "' + parameter_name + '" is not a function.');
        return false;
    }
    return true;
}



//
// Основные скрипты
//

function setClickHandlerOnForm(form_selector, clickHandler) {
    if(!checkString(form_selector, 'form_selector', 'setClickHandlerOnForm')) {
        return;
    };
    if(!checkFunction(clickHandler, 'clickHandler', 'setClickHandlerOnForm')) {
        return;
    };


    let elem_form = document.querySelector(form_selector) ?? null;
    if (elem_form === null) {
        console.log('Error > setClickHandlerOnForm > selector not found!');
        return;
    }

    let button_submit = elem_form.querySelector('input[type="submit"]');
    button_submit.addEventListener("click", (e) => {
        e.preventDefault();
        clickHandler(elem_form);
    });
}

function setClickHandlerOnElem(selector, clickHandler) {
    if(!checkString(selector, 'selector', 'setClickHandlerOnElem')) {
        return;
    };
    if(!checkFunction(clickHandler, 'clickHandler', 'setClickHandlerOnElem')) {
        return;
    };


    let elem = document.querySelector(selector) ?? null;
    if (elem === null) {
        console.log('Error > setClickHandlerOnElem > selector not found!');
        return;
    }

    elem.addEventListener("click", (e) => {
        e.preventDefault();
        clickHandler(elem);
    });
}


async function serverRequest(url, body_data, responseHandler) {
    if(!checkString(url, 'url', 'serverRequest')) {
        return;
    };
    if(!checkObject(body_data, 'body_data', 'serverRequest')) {
        return;
    };
    if(!checkFunction(responseHandler, 'responseHandler', 'serverRequest')) {
        return;
    };

    let response_data_packet = {
        success: false,
        type: 'none',
        message: '',
        data: [],
    }

    let response = await fetch(
        url,
        {
            method: 'POST',
            body: body_data
        }
    );
    let response_data = await response.json();

    if ( typeof response_data.success === 'boolean') {
        response_data_packet.success = response_data.success ?? '';
    }

    if (typeof response_data?.type === 'string' && response_data.type !== '') {
        response_data_packet.type = response_data.type ?? '';
    }

    if (typeof response_data?.message === 'string' && response_data.message !== '') {
        response_data_packet.message = response_data.message ?? '';
    }

    if (typeof response_data?.data === 'string' && response_data.data !== '') {
        response_data_packet.data = JSON.parse(response_data.data);;
    }

    responseHandler(response_data_packet);

    return response_data;
}

function setContent(selector, content) {
    if(!checkString(selector, 'selector', 'setContent')) {
        return;
    };
    if(!checkString(content, 'content', 'setContent')) {
        return;
    };

    let container = document.querySelector(selector) ?? null;
    if (container === null) {
        console.log('Error > setContent > selector not found!');
        return;
    }

    container.innerHTML = content;
}

function sidebarUpdate(selector) {
    if(!checkString(selector, 'selector', 'controlMenuItem')) {
        return;
    };

    let sidebar = document.querySelector(selector) ?? null;
    if (sidebar === null) {
        console.log('Error > sidebarUpdate > selector not found!');
        return;
    }

    let menu_items = sidebar.querySelectorAll('menu > li.activated');
    menu_items.forEach(function (item) {
        item.classList.remove('activated');
    });
}

function controlMenuItem(selector, selector_container, check_activated_class = true) {
    if(!checkString(selector, 'selector', 'controlMenuItem')) {
        return;
    };
    if(!checkString(selector_container, 'selector_container', 'controlMenuItem')) {
        return;
    };
    if(!checkBoolean(check_activated_class, 'check_activated_class', 'controlMenuItem')) {
        return;
    };

    let menu_items = document.querySelectorAll(selector) ?? null;
    if (menu_items === null) {
        console.log('Error > controlMenuItem > selector not found!');
        return;
    }

    menu_items.forEach(
        function (item) {
            let tag_data = item.querySelector('data') ?? null;
            if (tag_data === null) {
                console.log('Error > controlMenuItem > selector not found!');
                return;
            }

            if (tag_data.hasAttribute('value')) {
                item.addEventListener('click', (e) => {
                    if (!item.classList.contains('activated') || !check_activated_class) {
                        e.preventDefault();
                        sidebarUpdate('.sidebar');
                        setContent(selector_container, '');

                        serverRequest(
                            tag_data.getAttribute('value'),
                            {},
                            (data_packet) => {
                                setContent(selector_container, data_packet.data[0] ?? '');
                                console.log(data_packet);
                            }
                        )

                        if (!check_activated_class) {
                            item.classList.add('activated');
                        }
                    }
                });
            }
        } // end function
    );
}