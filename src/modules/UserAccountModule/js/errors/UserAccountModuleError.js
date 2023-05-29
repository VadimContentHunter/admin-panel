export default class UserAccountModuleError extends Error {
    constructor(message) {
        super(message);
        this.name = 'UserAccountModuleError';
    }
}
