import MainLibraryError from './MainLibraryError.js';

export default class SidebarUpdateError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = 'SidebarUpdateError';
    }
}
