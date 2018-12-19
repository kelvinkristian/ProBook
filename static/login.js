function updateButton() {
    let submit = document.getElementById("submit");
    for (let element of document.getElementsByTagName("input")) {
        if (element.value === "" && !element.id.includes("oauth")) {
            submit.disabled = true;
            return;
        }
    }
    submit.disabled = false;
}

for (let element of document.getElementsByTagName("input")) {
    if (!element.classList.contains("check") && !element.id.includes("oauth")) {
        element.onchange = updateButton;
    }
}

updateButton();