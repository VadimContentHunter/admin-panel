export default class SelectElementError extends Error {
    constructor(message) {
        super(message);
        this.name = 'SelectElementError';
    }
}
