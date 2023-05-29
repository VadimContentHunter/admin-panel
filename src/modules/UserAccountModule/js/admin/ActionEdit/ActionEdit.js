import ActionEditError from './errors/ActionEditError.js';

function InputReadOnly(selectorForm, activate) {
    let elemForm = document.querySelector(selectorForm) ?? null;
    if (!(elemForm instanceof HTMLFormElement)) {
        throw new ActionEditError('selector for form, not found!');
    }

    let inputs = elemForm.querySelectorAll('input');

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

export function ActionEdit(selectorButton, selectorPanelFooter, selectorForm = 'form') {
    let elemButton = document.querySelector(selectorButton) ?? null;
    if (!(elemButton instanceof HTMLElement)) {
        throw new ActionEditError('selector for button, not found!');
    }

    let elemPanelFooter = document.querySelector(selectorPanelFooter) ?? null;
    if (!(elemPanelFooter instanceof HTMLElement)) {
        throw new ActionEditError('selector for Panel Footer, not found!');
    }

    elemButton.addEventListener('click', (e) => {
        e.preventDefault();

        if (elemPanelFooter.style.display !== 'flex') {
            elemPanelFooter.style.display = 'flex';
            InputReadOnly(selectorForm, false);
        } else {
            elemPanelFooter.style.display = '';
            InputReadOnly(selectorForm, true);
        }
    });
}
