"use strict";

function setClickHandlerOnForm(form_selector, clickHandler) {
    let elem_form = document.querySelector(form_selector) ?? null;
    if (elem_form === null) {
        console.log('Error!');
        return;
    }

    let button_submit = elem_form.querySelector('input[type="submit"]');
    button_submit.addEventListener("click", (e) => {
        e.preventDefault();
        clickHandler(elem_form);
    });
}

function setClickHandlerOnElem(selector, clickHandler) {
    let elem = document.querySelector(selector) ?? null;
    if (elem === null) {
        console.log('Error2!');
        return;
    }

    elem.addEventListener("click", (e) => {
        e.preventDefault();
        clickHandler(elem);
    });
}


async function serverRequest(url, body_data, ResponseHandler) {
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

    ResponseHandler(response_data_packet);

    return response_data;
}