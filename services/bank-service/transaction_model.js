const mysql = require('mysql');
const config = require('./config');
const Database = require('./database');

class TransactionModel {
    constructor() {
        this.id = 0;
        this.sender_card_number = "";
        this.receiver_card_number = "";
        this.value = 0.0;
        this.db = new Database({
            host: config.MYSQL_HOST,
            user: config.MYSQL_USER,
            password: config.MYSQL_PASS,
            database: config.MYSQL_DB
        });
    }

    async load(unique_column) {
        let query = "SELECT * FROM transaction where ?? = ? LIMIT 1";
        let params = [unique_column, this[unique_column]];
        let sql = mysql.format(query, params);

        let result = await this.db.query(sql);
        this.id = result[0].id;
        this.name = result[0].name;
        this.card_number = result[0].card_number;
        this.balance = result[0].balance;
    }

    async insert() {
        let query = "insert into transaction (sender_card_number, receiver_card_number , value) values (?, ?, ?)";
        let params = [this.sender_card_number, this.receiver_card_number, this.value];
        let sql = mysql.format(query, params);

        let result = await this.db.query(sql);
    }

    // async save() {
    //     let query = "update client set balance = ? where id = ?";
    //     let params = [this.balance, this.id];
    //     let sql = mysql.format(query, params);

    //     let result = await this.db.query(sql);
    // }
}

module.exports = TransactionModel;
