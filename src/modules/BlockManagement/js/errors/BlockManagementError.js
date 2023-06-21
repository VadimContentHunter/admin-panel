export default class BlockManagementError extends Error {
    constructor(message) {
        super(message);
        this.name = 'BlockManagementError';
    }
}
