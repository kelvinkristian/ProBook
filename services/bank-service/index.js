const express = require('express');
const client_model = require('./client_model');
const transaction_model = require('./transaction_model');
const config = require('./config');
const generate_otp = require('./TOTP');
const app = express();
const port = 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true}));
app.use(function (req, res, next) {
    res.set('Access-Control-Allow-Origin', '*');
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
});

app.get('/', (req, res) => res.send('Hello World!'));

app.post('/validate', async (req, res) => {
    let model = new client_model();
    model.card_number = req.body.card_number;

    try {
        await model.load('card_number');
    } catch (e) {
        console.log(e);
    }

    let result = {
        is_valid: model.id !== 0
    };
    res.send(JSON.stringify(result));
});

app.post('/transfer', async (req, res) => {
    let otp = await generate_otp(req.body.card_number_sender, 30);
    console.log(otp);
    console.log(req.body.otp);
    if (otp !== req.body.otp) {
        let result =  {
            success: false,
            message: 'Invalid One Time Password'
        };
        res.send(JSON.stringify(result));
        return;
    }
    let model_receiver = new client_model();
    let model_sender = new client_model();
    let model_transaction = new transaction_model();

    model_receiver.card_number = req.body.card_number_receiver;
    model_sender.card_number = req.body.card_number_sender;
    try {
        await model_sender.load('card_number');
        await model_receiver.load('card_number');
    } catch (e) {
        console.log(e);
    }

    let value = req.body.value;
    let success = false;
    let message = "Sender balance not enough to send";
    if (value <= 0) message = "Value must be positive";
    else if (model_sender.id === 0) message = "Sender Card Number not found!";
    else if (model_receiver.id === 0) message = "Receiver Card Number not found!";
    else if (model_sender.balance - value >= 0) {
        model_sender.balance = model_sender.balance - value;
        model_receiver.balance = model_receiver.balance + value;
        model_transaction.receiver_card_number = model_receiver.card_number;
        model_transaction.sender_card_number = model_sender.card_number;
        model_transaction.value = value;
        try {
            await model_transaction.insert();
            await model_receiver.save();
            await model_sender.save();
        } catch (e) {
            console.log(e);
        }
        success = true;
        message = "Success";
    }

    let result = {
        success: success,
        message: message
    };
    res.send(JSON.stringify(result));
});

app.listen(port, () => console.log(`PORT : ${port}`));
