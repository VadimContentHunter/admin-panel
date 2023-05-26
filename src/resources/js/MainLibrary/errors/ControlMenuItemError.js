import MainLibraryError from './MainLibraryError.js';

export default class ControlMenuItemError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = 'ControlMenuItemError';
    }
}
