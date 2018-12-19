# Tugas 2 IF3110 Pengembangan Aplikasi Berbasis Web 

## ProBook

## Explanation

Probook is a book web application. This web application use two piece of services which is bank service and book service.

### Bank Service
The bank service use Javascript for its' operation. The Javascript code is run on node.js machine. For exchanging data, it use JSON (Java Script Object Notation). The bank service is the service that is in charge for handling client data and transaction data. Each book order transaction will be noted at the transaction table so we can track every transaction correctly. Every client is noted at client table and have their own unique credit card number that is used for tracking their transaction at transaction table. The client table is consist of id, name, card number and balance. The transaction table is consist of id, sender card number, receiver card number, value and created_at.

### Book Service
The book service use Java API for XML Web Services (JAX-WS). It is a set of API for creating web services in XML formal (SOAP). SOAP (Simple Object Access Protocol) is a message protocol for exchanging data between client and server. The book service is the service that is in charge for handling book data that is retrieved from Google Books using Google Books API. For retrieving book data, it need GSON library because the Google Books use special type of JSON. Everytime user is searching for books, the retrieved data are saved in book servicedatabase. Everytime user is ordering for books, the order are saved in ordered_book table to be tracked by bank_service for transaction purpose.

Book service has 4 tables which is book, book genre, genre, ordered book. Book table consist of id, book id, authors, titel, description, price and img. Book genre table has id, book id and genre id. Genre table has id and genre. Ordered book table has id, book id, amount and buyer account number.

### ProBook 
ProBook is the user interface for searching and buying books. For using ProBook regitration is needed. After having an account, ProBook user can use ProBook services which is search book, look book details and order book. Every book searched is stored in book service database. Every order created, the order is stored at bank service database. 

Probook has 5 tables which is book, review, session, user and order.The book table consist of id, name, author, description, vote, rating, imgsrc. The review table consist of id, comment and star. The session table consist of id, session_id, user_id and expire. The user table consist of id, name, username, email, password, address, phone, avatar, card number. Order table consist of id, book id, user id, review id, amount and created_at.

#### Overall process scheme
![](temp/architecture.png)

### Shared session concept
HTTP is stateless. To put it simply, the server doesn't know that you exist
until you made a request to the server. After replying to your request, it
will then forget about your existence again.

Unless at least one side of the party implements a state saving mechanism
which is agreed by both side. Then the client & server will be able
to keep track on what has been going on.

Usually, we called the state-saving mechanism as session. And because of the 
statelessness of HTTP, it is usually implemented as a token, either as a hash
or just a randomly generated string.

The problem is : REST services are not designed to hold the session/state of the
application. So the client must hold all of the application state and also
handle it by itself. So there are no shared sessions between REST services
with the clients.

Don't get this wrong though, the server still needs to know which client
has what kind of authorization. The server/service still know what each client
are able to do, what kind of permissions that they have.

So instead of

    Server : "I see that Client A is requesting data D. Based on his state and action before..."

the server will just

    Server : "I see that Client A is requesting data D. Does he have the permission to get it?"

So there are a few advantages not sharing the application state. The
services are more lightweight and faster since there are less to process.


### Token invocation and expiry time

##### Session Token
    1. Generate a 30 char random string
    2. Save it to DB along with IP, expiry, user agent, and the user id
    3. Check it on every request, expiry is set to be 1 day on default

##### Bank Token
    1. HMAC_SHA1(account_number, UNIX_TIME//30)
    2. Get the last byte of the hash as offset
    3. Get hash[offset... offset+3] & 0x7fffffff
    4. Return first 8 digits

### Pros and Cons of our application 

#### Pros
        1. Independent, separation of concern kept well.
        2. Scalable. Each services can be scaled easily on its own because they are loosely coupled.
        3. Modular. Easy to add new features if implemented correctly.
#### Cons
        1. Take time to start all services
        2. Hard to develop. Prone to developer mistakes since it's hard to be integration tested.
        3. Huge overhead. HTTP calls are expensive.

## How to run

### Run Book Webservice
1. Open your IDE, we use Intellij IDEA to simplify
2. Use tugasbesar2_2018\services\book-service as the source path
3. Run tugasbesar2_2018\services\book-service\src\client\SearchBookClient.java & tugasbesar2_2018\services\book-service\src\endpoint\BookServicePublisher.java

### Run Bank Webservice
1. Open tugasbesar2_2018\services\bank-service\
2. Add config.js file for your own database in that path
3. Open command line in that path
4. Type "npm install"
5. Type "node index.js"

### Run Probook
1. Open tugasbesar2_2018\
2. Add config.php file for your own database in tugasbesar2_2018\core
3. Open command line in tugasbesar2_2018\
4. Type "php -S localhost:5000"

## Meet Our Team

    1. Ahmad Faiz Sahupala      13516065
    2. Kelvin Kristian          13516101
    3. Gabriel Raphael Bentara  13516119

### Task Distribution

1. REST :
    1. Validasi nomor kartu                         : 13516119
    2. Registration card number with card number    : 13516101
    3. Profile and change profile with card number  : 13516065
    4. Search book (proxy soap)                     : 13516119
    5. Order book  (php)                            : 13516119
    6. Transfer                                     : 13516065 & 13516101

2. SOAP :
    1. Book Webservice database creation    : 13516101
    2. Searchbook client & endpoint         : 13516119
    3. Book structs                         : 13516119
    4. saveBook Webservice                  : 13516101
    5. orderBook Webservice                 : 13516101
    6. searchBookGenre Webservice           : 13516065

3. Web App Changes :
    1. Search page          : 13516119
    2. Login page           : 13516065
    3. Registration page    : 13516101
    4. Order book           : 13516119
    5. History              : 13516119

4. Bonus :
    1. HOTP/TOTP token invocation   : 13516119
    2. Token Validation             : 13516119
    3. Google sign-in API           : 13516065

Created by : Ahmad Faiz Sahupala | Kelvin Kristian | Gabriel Raphael Bentara