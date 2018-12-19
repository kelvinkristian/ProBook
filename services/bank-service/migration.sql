CREATE TABLE client (
    id int not null auto_increment,
    name varchar(100) not null,
    card_number varchar(30) not null, 
    INDEX USING BTREE (card_number),
    balance float default 0.0,
    primary key (id)
);

CREATE TABLE transaction (
    id int not null auto_increment,
    sender_card_number varchar(30) not null,
    receiver_card_number varchar(30) not null,
    value float not null,
    primary key (id),
    foreign key (sender_card_number) references client(card_number),
    foreign key (receiver_card_number) REFERENCES client(card_number)
);
