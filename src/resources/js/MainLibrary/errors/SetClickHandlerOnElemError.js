import MainLibraryError from './MainLibraryError.js';

export default class SetClickHandlerOnElemError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = 'SetClickHandlerOnElemError';
    }
}
