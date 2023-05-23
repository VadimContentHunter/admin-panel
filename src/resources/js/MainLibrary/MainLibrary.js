import MainLibraryError from './errors/MainLibraryError.js';

export function setClickHandlerOnElem(selector, clickHandler) {
    if (typeof selector !== 'string') {
        throw new MainLibraryError('Тип параметра selector для функции setClickHandlerOnElem, должен быть "string"');
    }

    if (typeof clickHandler !== 'function') {
        throw new MainLibraryError(
            'Тип параметра clickHandler для функции setClickHandlerOnElem, должен быть "function"',
        );
    }

    let elem = document.querySelector(selector) ?? null;
    if (elem === null) {
        throw new MainLibraryError('Параметра selector для функции setClickHandlerOnElem, не найден.');
    }

    elem.addEventListener('click', (e) => {
        e.preventDefault();
        clickHandler(elem);
    });
}
