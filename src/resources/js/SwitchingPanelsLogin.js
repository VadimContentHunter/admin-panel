let { setClickHandlerOnElem } = await import(
    pathResourcesJs + "/MainLibrary/MainLibrary.js"
);

// import { setClickHandlerOnElem } from "./MainLibrary/MainLibrary.js";

setClickHandlerOnElem("#container_sing_in .panel-footer a", (elemContainer) => {
    let elemRegister = document.querySelector("#container_register") ?? null;
    if (elemRegister === null) {
        throw new Error("Error!");
    }
    elemRegister.style.display = "block";

    let elemParent = elemContainer.closest(".login-container");
    elemParent.style.display = "none";
});

setClickHandlerOnElem(
    "#container_register .panel-footer a",
    (elemContainer) => {
        let elemSingIn = document.querySelector("#container_sing_in") ?? null;
        if (elemSingIn === null) {
            throw new Error("Error!");
        }
        elemSingIn.style.display = "block";

        let elemParent = elemContainer.closest(".login-container");
        elemParent.style.display = "none";
    }
);
