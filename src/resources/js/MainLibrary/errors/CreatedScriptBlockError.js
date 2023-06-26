if (typeof pathNodeModules === "undefined") {
    throw Error("Ошибка укажите переменную pathNodeModules");
}

if (typeof pathResourcesJs === "undefined") {
    throw Error("Ошибка укажите переменную pathResourcesJs");
}

let MainLibraryError = (
    await import(pathResourcesJs + "/MainLibrary/errors/MainLibraryError.js")
).default;

export default class CreatedScriptBlockError extends MainLibraryError {
    constructor(message) {
        super(message);
        this.name = "CreatedScriptBlockError";
    }
}
