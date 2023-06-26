if (typeof pathModule === "undefined") {
    throw Error("Ошибка укажите переменную pathModule");
}

let { ActionEdit } = await import(
    pathModule +
        "/UserAccountModule/js/admin/ActionEdit/ActionEdit.js"
);

// import { ActionEdit } from "../../../../../src/modules/UserAccountModule/js/admin/ActionEdit/ActionEdit.js";

ActionEdit(
    ".container-one .panel-header a",
    ".container-one form .panel-footer"
);
