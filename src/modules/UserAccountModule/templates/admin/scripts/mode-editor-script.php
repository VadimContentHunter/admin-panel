<script>
    let selector_button = '.container-one .panel-header a';
    let selector_panel_footer = '.container-one form .panel-footer';
    alert(selector_button);
    (function (selector_button, selector_panel_footer) {
        alert(selector_button);

        let elem_button = document.querySelector(selector_button) ?? null;
        if (elem_button === null) {
            console.log('Error > mode-editor-script > selector not found!');
            return;
        }

        let elem_panel_footer = document.querySelector(selector_panel_footer) ?? null;
        if (elem_panel_footer === null) {
            console.log('Error > mode-editor-script > selector not found!');
            return;
        }

        elem_button.addEventListener("click", (e) => {
            e.preventDefault();

            if(elem_panel_footer.style.display !== "flex")
                elem_panel_footer.style.display = "flex";
            else
                elem_panel_footer.style.display = "";
        });
    })(selector_button, selector_panel_footer);
</script>