import MainLibraryError from './MainLibraryError.js';

export default class CreatedScriptBlockError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = 'CreatedScriptBlockError';
    }
}
