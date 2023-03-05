"use strict";

function setClickHandlerOnForm(form_selector, clickHandler) {
    let elem_form = document.querySelector(form_selector) ?? null;
    if (elem_form === null) {
        console.log('Error!');
        return;
    }

    let button_submit = document.querySelector('input[type="submit"]');
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
    let response = await fetch(
        url,
        {
            method: 'POST',
            body: body_data
        }
    );
    let response_data = await response.json();

    ResponseHandler(response_data);

    return response_data;
}