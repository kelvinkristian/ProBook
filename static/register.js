function invalidBorderColor(elem) {
    elem.style.borderColor = "red";
}

function validBorderColor(elem) {
    elem.style.borderColor = "green";
}

async function checkUsername() {
    let user = document.getElementById("username");
    let check = user.nextElementSibling;
    let username = user.value;

    if (username.length <= 0 || username.length > 20) {
        invalidBorderColor(user);
        check.style.display = "none";
        return;
    }


    let result = await fetch("/check-user/" + username);
    let textResult = await result.text();

    console.log(textResult);

    if (textResult === "ok") {
        validBorderColor(user);
        check.style.display = "inline-flex";
    } else {
        invalidBorderColor(user);
        check.style.display = "none";
    }
    updateButton();
}

async function checkEmail() {
    let emailField = document.getElementById("email");
    let check = emailField.nextElementSibling;
    let email = emailField.value;

    let regex = /[a-zA-Z0-9_.]+@[a-zA-Z]+.[a-zA-Z]+/g;
    if (!regex.test(email)) {
        invalidBorderColor(emailField);
        check.style.display = "none";
        return;
    }

    let result = await fetch("/check-email?email=" + email);
    let textResult = await result.text();

    console.log(textResult);

    if (textResult === "ok") {
        validBorderColor(emailField);
        check.style.display = "inline-flex";
    } else {
        invalidBorderColor(emailField);
        check.style.display = "none";
    }
    updateButton();
}

async function checkCard() {
    let cardField = document.getElementById("card");
    let card = cardField.value;
    let submit = document.getElementById("submit");

    
    let regex = /[0-9]{16}/i;
    console.log(card);
    console.log(regex.test(card));
    if ((card.length != 16) || ((!regex.test(card)))) {
        invalidBorderColor(cardField);
        submit.disabled = true;
        return;
    }
    

    // let result = await fetch("/check-card?card=" + card);
    // let textResult = await result.text();

    // console.log(textResult);


    // if (textResult === "ok") {
    //     validBorderColor(cardField);
    //     check.style.display = "inline-flex";
    // } else {
    //     invalidBorderColor(cardField);
    //     check.style.display = "none";
    // }

    let callBack = function(data) {
        if (!data["is_valid"]) {
            cardField.style.borderColor = "red";
            alert("card not found in web services");
            submit.disabled = true;
        } else {
            submit.disabled = false;
            cardField.style.borderColor = "green";
        }
    };

    validateCard("http://localhost:3000/validate", card, callBack);

    // validBorderColor(cardField);
}

function updateButton() {
    let emailField = document.getElementById("email");
    let checkEmail = emailField.nextElementSibling;
    let user = document.getElementById("username");
    let checkUser = user.nextElementSibling;
    let submit = document.getElementById("submit");

    if (checkUser.style.display !== "none" && checkEmail.style.display !== "none") {
        submit.disabled = false;
    } else {
        submit.disabled = true;
    }

    for (let element of document.getElementsByTagName("input")) {
        if (element.classList.contains("check") || element.type === "submit") {
            continue;
        }

        if (element.id === "phone") {
            let regex = /[0-9]{9,12}/i;
            if (!regex.test(element.value)) {
                invalidBorderColor(element);
                submit.disabled = true;
                continue;
            }
        }

        if (element.id === 'card') {
            checkCard();
            continue;
        }

        if (element.value === "") {
            invalidBorderColor(element);
            submit.disabled = true;
            continue;
        }

        validBorderColor(element);
    }

    let textArea = document.getElementById("address");
    textArea.onchange = updateButton;

    if (textArea.value === "") {
        invalidBorderColor(textArea);
        submit.disabled = true;
        return;
    }
    validBorderColor(textArea);
}

for (let element of document.getElementsByTagName("input")) {
    if (!element.classList.contains("check")) {
        element.onchange = updateButton;
    }
}

document.onload = updateButton;