const mysql = require('mysql');
const config = require('./config');
const Database = require('./database');

db = new Database({
    host: config.MYSQL_HOST,
    user: config.MYSQL_USER,
    password: config.MYSQL_PASS,
    database: config.MYSQL_DB
});

let query = "alter table transaction add column created_at timestamp default current_timestamp not null AFTER value;";
db.query(query);