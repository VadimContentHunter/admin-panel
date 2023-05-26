import MainLibraryError from './MainLibraryError.js';

export default class SetContentError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = 'SetContent';
    }
}
