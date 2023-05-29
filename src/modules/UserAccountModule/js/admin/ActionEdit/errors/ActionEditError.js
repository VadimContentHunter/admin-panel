import UserAccountModuleError from '../../../errors/UserAccountModuleError.js';

export default class ActionEditError extends UserAccountModuleError {
    constructor(message) {
        super(message);
        this.name = 'ActionEditError';
    }
}
