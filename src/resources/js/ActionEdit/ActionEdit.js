import ActionEditError from './errors/ActionEditError.js';

function InputReadOnly(selectorForm, activate) {
    let elemForm = document.querySelector(selectorForm) ?? null;
    if (!(elemForm instanceof HTMLFormElement)) {
        throw new ActionEditError('selector for form, not found!');
    }

    let inputs = elemForm.querySelectorAll('input:not([type="button"])');

    inputs.forEach((elemInput) => {
        if (elemInput instanceof HTMLInputElement) {
            if (!activate) {
                // eslint-disable-next-line no-param-reassign
                elemInput.style.color = '#000000';
                elemInput.removeAttribute('readonly');
            } else {
                // eslint-disable-next-line no-param-reassign
                elemInput.style.color = '';
                elemInput.setAttribute('readonly', null);
            }
        }
    });
}

export function EditStatus(selectorPanelFooter, selectorForm = 'form', status = null) {
    let elemPanelFooter = document.querySelector(selectorPanelFooter) ?? null;
    if (!(elemPanelFooter instanceof HTMLElement)) {
        throw new ActionEditError('selector for Panel Footer, not found!');
    }

    if (typeof status !== 'boolean' && status !== null) {
        throw new ActionEditError('The status is not of type boolean or null.');
    }

    if (status === null) {
        if (elemPanelFooter.style.display !== 'flex') {
            elemPanelFooter.style.display = 'flex';
            InputReadOnly(selectorForm, false);
        } else {
            elemPanelFooter.style.display = '';
            InputReadOnly(selectorForm, true);
        }
    } else if (status) {
        if (elemPanelFooter.style.display !== 'flex') {
            elemPanelFooter.style.display = 'flex';
            InputReadOnly(selectorForm, false);
        }
    } else {
        elemPanelFooter.style.display = '';
        InputReadOnly(selectorForm, true);
    }
}

export function ActionEdit(selectorButton, selectorPanelFooter, selectorForm = 'form') {
    let elemButton = document.querySelector(selectorButton) ?? null;
    if (!(elemButton instanceof HTMLElement)) {
        throw new ActionEditError('selector for button, not found!');
    }

    elemButton.addEventListener('click', (e) => {
        e.preventDefault();

        EditStatus(selectorPanelFooter, selectorForm);
    });
}
