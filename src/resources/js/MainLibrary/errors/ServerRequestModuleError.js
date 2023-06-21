import MainLibraryError from './MainLibraryError.js';

export default class ServerRequestModuleError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = 'ServerRequestModuleError';
    }
}
