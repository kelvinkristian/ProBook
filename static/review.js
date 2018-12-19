for (let i = 0; i < 5; i++) {
    let star = document.getElementById("star-" + i);
    star.onmouseover = () => {
        for (let j = 0; j <= i; j++) {
            let star = document.getElementById("star-" + j);
            if (star == null) {
                continue;
            }
            star.src = "/static/star.png";
        }
    };

    star.onmouseout = () => {
        let x = -1;
        for (let j = 5; j >= 0; j--) {
            if (document.getElementById("star-" + j + "-clicked") !== null) {
                x = j;
                break;
            }
        }

        for (let j = x+1; j <= i; j++) {
            let star = document.getElementById("star-" + j);
            star.src = "/static/star-o.png";
        }
    };

    star.onclick = () => {
        for (let j = 0; j < 5; j++) {
            let star = document.getElementById("star-" + j + "-clicked");
            if (star != null) {
                star.id = "star-" + j;
            }
            star = document.getElementById("star-" + j);
            star.src = "/static/star-o.png";
        }

        for (let j = 0; j <= i; j++) {
            let star = document.getElementById("star-" + j);
            star.src = "/static/star.png";
        }

        if (star.id !== "star-" + i + "-clicked") {
            star.id += "-clicked";
        }

        let input = document.getElementById("star-input");
        input.value = i + 1;
    }
}

function updateButton() {
    let button = document.getElementById("submit");
    let star = document.getElementById("star-input");
    let comment = document.getElementById("comment");

    if (star > 5 || star < 1) {
        button.disabled = true;
        return;
    }

    if (comment.value === "") {
        button.disabled = true;
        return;
    }

    button.disabled = false;
}

let star = document.getElementById("star-input");
let comment = document.getElementById("comment");
star.onchange = updateButton;
comment.onchange = updateButton;

updateButton();