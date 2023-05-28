import NotificationError from './errors/NotificationError.js';

export class Notification {
    #mainElement;
    #listElement;
    #counterElement;
    #listNotifications = [];
    static maxListLength = 15;
    static tagNew = '__push';

    constructor(selectorMainElement, selectorListElement, selectorCounterElement) {
        if (typeof selectorMainElement !== 'string') {
            throw new NotificationError('Тип параметра selectorMainElement, должен быть "string"');
        }

        if (typeof selectorListElement !== 'string') {
            throw new NotificationError('Тип параметра selectorListElement, должен быть "string"');
        }

        if (typeof selectorCounterElement !== 'string') {
            throw new NotificationError('Тип параметра selectorCounterElement, должен быть "string"');
        }

        this.mainElement = document.querySelector(selectorMainElement);
        this.listElement = document.querySelector(selectorListElement);
        this.counterElement = document.querySelector(selectorCounterElement);

        this.setNumberNotifications(0);
        this.disableMainBlock();
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
        if (!Array.isArray(this.#listNotifications)) {
            throw new NotificationError('Свойство listNotifications, должно быть "array"');
        }
        return this.#listNotifications;
    }

    updateListElement() {
        let items = this.listElement.querySelectorAll('article.' + Notification.tagNew) ?? [];
        if (items.length === 0) {
            this.setStatusNew(false);
        }
        this.setNumberNotifications(items.length);
    }

    deleteItemsListNotifications() {
        this.#listNotifications.forEach((item) => {
            if (item instanceof HTMLElement) {
                item.remove();
            }
        });
    }

    disableListElement() {
        let items = this.listElement.querySelectorAll('article.' + Notification.tagNew) ?? [];
        items.forEach((item) => {
            if (item.classList.contains(Notification.tagNew)) {
                item.classList.remove(Notification.tagNew);
            }
        });
        this.setNumberNotifications(0);
    }

    disableMainBlock() {
        let hide = false;
        this.mainElement.addEventListener('mouseover', (event) => {
            if (!hide) {
                hide = true;
            }
        });

        // mouseout
        // Добавление события для теневого дерева меню
        // (Что бы оно скрывалось если клик не в области элемента)
        document.addEventListener('click', (e) => {
            if (
                !e.composedPath().includes(this.mainElement) &&
                hide &&
                this.mainElement.classList.contains(Notification.tagNew)
            ) {
                hide = false;
                this.disableListElement();
                this.updateListElement();
            }
        });
    }

    setNumberNotifications(value) {
        this.counterElement.setAttribute('value', Number(value));
    }

    getNumberNotifications() {
        if (this.counterElement.hasAttribute('value') && this.counterElement.getAttribute('value') > 0) {
            return Number(this.counterElement.getAttribute('value'));
        }

        return 0;
    }

    addAutoNumberNotifications() {
        let currentNumber = this.getNumberNotifications();
        this.setNumberNotifications(currentNumber + 1);
    }

    setStatusNew(status = true) {
        if (typeof status !== 'boolean') {
            throw new NotificationError('Тип параметра status, должен быть "boolean"');
        }

        if (status && !this.mainElement.classList.contains(Notification.tagNew)) {
            this.mainElement.classList.add(Notification.tagNew);
        }

        if (!status && this.mainElement.classList.contains(Notification.tagNew)) {
            this.mainElement.classList.remove(Notification.tagNew);
        }
    }

    deleteDefaultElement() {
        if (this.listNotifications.length > 0) {
            let elemDefault = this.listElement.querySelector('.default');
            if (elemDefault instanceof HTMLElement) {
                elemDefault.remove();
            }
        }
    }

    trimStorage() {
        // let temp = this.listNotifications;
        // let t = this.#listNotifications.slice(Notification.maxListLength);
        // this.#listNotifications = temp;
        // console.log(t);

        let temp = this.listNotifications;
        this.#listNotifications = [];
        for (let i = 0; i < temp.length; i += 1) {
            if (i < Notification.maxListLength) {
                this.#listNotifications.push(temp[i]);
            }
        }

        temp.forEach((item) => {
            if (item instanceof HTMLElement) {
                item.remove();
            }
        });

        // this.setNumberNotifications(this.#listNotifications.length);
    }

    addNotification(title, titleDate, message, newNotification = true) {
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

        itemArticleElement.append(itemHeaderElement, itemContentElement);
        if (newNotification) {
            itemArticleElement.classList.add(Notification.tagNew);
        }

        this.#listNotifications.unshift(itemArticleElement);

        this.addAutoNumberNotifications();
        return this;
    }

    update() {
        this.deleteDefaultElement();
        this.trimStorage();
        this.listNotifications.forEach((item) => {
            this.listElement.append(item);
        });
    }
}
