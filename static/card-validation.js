function validateCard(url, cardNumber, callBack) {
    let postData = {"card_number" : cardNumber};
    let headers = {'Content-Type': 'application/json'};

    console.log(postData);

    fetch(url, {
        method: 'POST',
        body: JSON.stringify(postData),
        headers: headers
    })
    .then((resp) => resp.json())
    .then( function(data) {
        callBack(data);
    })
    .catch(function(error) {
        console.log(error);
    });
}