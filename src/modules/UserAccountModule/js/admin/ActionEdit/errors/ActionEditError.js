export default class ActionEditError extends Error {
    constructor(message) {
        super(message);
        this.name = 'ActionEditError';
    }
}
