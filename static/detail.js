window.onload = function() {
  document.getElementById("orderbutton").onclick = async function() {
    // create request

    let order_count = parseInt(
      document.getElementById("order-select").selectedOptions[0].value
    );

    if (order_count == 0) {
      let e = document.getElementById("notif-error");
      e.innerText = "Order count can't be 0.";
      document.getElementById("notif-content").style.display = "none";
      e.style.display = "block";
    } else {
      document.getElementById("notif-content").style.display = "none";
      document.getElementById("notif-error").style.display = "none";
      let data = new FormData();
      data.append("order_count", order_count);

      data.append("book_id", parseInt(document.getElementById("book-id").innerText));
      data.append("otp", document.getElementById('otp').value);

      let resp = await fetch("/order", {
        method: "POST",
        body: data
      });

      console.log(await resp.body)

      let body = await resp.json();
        console.log(body);
      if (body.status !== "ok") {
        let e = document.getElementById("notif-error");
        e.innerText = "Order fail. " + body.message;
        e.style.display = "block";
      } else {
        // show notification
        document.getElementById("notif-content").style.display = "flex";
        document.getElementById("order-count").innerHTML = body.order_id;
      }
    }

    document.getElementById("overlay").style.display = "flex";
  };

  document.getElementById("notif-close").onclick = function() {
    document.getElementById("overlay").style.display = "none";
  };

  document.getElementById("overlay").onclick = function() {
    document.getElementById("overlay").style.display = "none";
  };
};
