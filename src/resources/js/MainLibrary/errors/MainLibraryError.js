export default class MainLibraryError extends Error {
    constructor(message) {
        super(message);
        this.name = 'MainLibrary';
    }
}
