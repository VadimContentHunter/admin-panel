import NotificationError from './errors/NotificationError.js';

export class Notification {
    #mainElement;
    #listElement;
    #counterElement;
    #listNotifications = [];

    constructor(selectorMainElement, selectorListElement, selectorCounterElement) {
        if (typeof selector !== 'string') {
            throw new NotificationError('Тип параметра selector, должен быть "string"');
        }
        this.mainElement = document.querySelector(selectorMainElement);
        this.listElement = document.querySelector(selectorListElement);
        this.counterElement = document.querySelector(selectorCounterElement);
    }

    set mainElement(value) {
        if (!(value instanceof HTMLElement)) {
            throw new NotificationError('Параметр value для свойства mainElement, должно быть "HTMLElement"');
        }

        this.#mainElement = value;
    }

    get mainElement() {
        if (!(this.#mainElement instanceof HTMLElement)) {
            throw new NotificationError('Свойство mainElement, должно быть "HTMLElement"');
        }
        return this.#mainElement;
    }

    set listElement(value) {
        if (!(value instanceof HTMLElement)) {
            throw new NotificationError('Параметр value для свойства listElement, должно быть "HTMLElement"');
        }
        this.#listElement = value;
    }

    get listElement() {
        if (!(this.#listElement instanceof HTMLElement)) {
            throw new NotificationError('Свойство listElement, должно быть "HTMLElement"');
        }
        return this.#listElement;
    }

    set counterElement(value) {
        if (!(value instanceof HTMLElement)) {
            throw new NotificationError('Параметр value для свойства counterElement, должно быть "HTMLElement"');
        }
        this.#counterElement = value;
    }

    get counterElement() {
        if (!(this.#counterElement instanceof HTMLElement)) {
            throw new NotificationError('Свойство counterElement, должно быть "HTMLElement"');
        }
        return this.#counterElement;
    }

    get listNotifications() {
        if (Array.isArray(this.#listNotifications)) {
            throw new NotificationError('Свойство listNotifications, должно быть "array"');
        }
        return this.#listNotifications;
    }

    getNumberNotifications() {
        if (this.counterElement.hasAttribute('value') && this.counterElement.getAttribute('value') < 0) {
            return this.counterElement.getAttribute('value');
        }

        return 0;
    }

    setStatusNew(status = true) {
        if (typeof status !== 'boolean') {
            throw new NotificationError('Тип параметра status, должен быть "boolean"');
        }

        if (status && this.mainElement.classList.contains('__push')) {
            this.mainElement.classList.add('__push');
        } else if (this.mainElement.classList.contains('__push')) {
            this.mainElement.classList.remove('__push');
        }

        return 0;
    }

    addNotification(title, titleDate, message, newNotification = false) {
        if (typeof title !== 'string') {
            throw new NotificationError('Тип параметра title, должен быть "string"');
        }

        if (typeof titleDate !== 'string') {
            throw new NotificationError('Тип параметра titleDate, должен быть "string"');
        }

        if (typeof message !== 'string') {
            throw new NotificationError('Тип параметра message, должен быть "string"');
        }

        if (typeof newNotification !== 'boolean') {
            throw new NotificationError('Тип параметра newNotification, должен быть "boolean"');
        }

        let itemArticleElement = document.createElement('article');
        let itemHeaderElement = document.createElement('h4');
        let itemHeaderTextElement = document.createElement('p');
        let itemHeaderTimeElement = document.createElement('time');
        let itemHeaderMenuElement = document.createElement('menu');
        let itemHeaderMenuButtonElement = document.createElement('button');
        let itemContentElement = document.createElement('a');

        itemHeaderMenuButtonElement.innerHTML = '<div class="icon-check"><i></i></div>';
        itemHeaderMenuButtonElement.classList.add('check');

        itemHeaderTimeElement.setAttribute('datetime', titleDate);
        itemHeaderTimeElement.innerText = titleDate;

        itemHeaderTextElement.innerText = title;
        itemHeaderTextElement.append(itemHeaderTimeElement);

        itemHeaderMenuElement.append(itemHeaderMenuButtonElement);

        itemHeaderElement.append(itemHeaderTextElement, itemHeaderMenuElement);

        itemContentElement.innerText = message;

        if (newNotification) {
            itemArticleElement.classList.add('__push');
        }
        itemArticleElement.append(itemHeaderElement, itemContentElement);

        itemArticleElement.addEventListener('mouseover', (event) => {
            if (itemArticleElement.classList.contains('__push')) {
                itemArticleElement.classList.remove('__push');
            }
        });

        this.#listNotifications.push(itemArticleElement);
        return this;
    }

    update() {
        this.listNotifications.forEach((item) => {
            this.listElement.append(item);
        });
    }
}
