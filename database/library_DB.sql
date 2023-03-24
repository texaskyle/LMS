-- table structure for table 'member'
CREATE TABLE IF NOT EXISTS member (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd CHAR(40) NOT NULL, 
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    status VARCHAR(20) 
);

-- inserting one member at the pending_registrations
INSERT INTO member(id, username, pwd, name, email) VALUES (1, "ivans", "7110eda4d09e062aa5e4a390b0a572ac0d2c0220", "ivans njoroge", "ivans@gmail.com");



-- table structure for table pending_registrations'
CREATE TABLE IF NOT EXISTS pending_registrations (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd CHAR(40) NOT NULL, 
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- inserting one member at the pending_registrations
INSERT INTO pending_registrations(id, username, pwd, name, email) VALUES (1, "evans", "7110eda4d09e062aa5e4a390b0a572ac0d2c0220", "evans njoroge", "evans@gmail.com");

-- table structure for table "librarian"
CREATE TABLE IF NOT EXISTS librarian(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd CHAR(40) NOT NULL
);

-- inserting data into the librarian table
INSERT INTO librarian(username, pwd) VALUES("librarian", '7110eda4d09e062aa5e4a390b0a572ac0d2c0220')

-- table structure for table 'book'
CREATE TABLE IF NOT EXISTS book(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    isbn CHAR(20) NOT NULL,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(80) NOT NULL,
    price INT(20),
    copies INT(10) NOT NULL
);

INSERT INTO book(id, isbn, title, author, category, price, copies) VALUES (1, '123', "Atomic Habits", "James Clear", "Education", 300, 30);

-- table structure for table 'pending_book_requests'

CREATE TABLE IF NOT EXISTS pending_book_requests(
    request_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    member varchar(40) NOT NULL,
    book_isbn VARCHAR(20) NOT NULL,
    time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- table structure for 'book_issue_log'

CREATE TABLE IF NOT EXISTS book_issue_log(
    issue_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    member VARCHAR(40) NOT NULL,
    book_isbn VARCHAR(20) NOT NULL,
    due_date TIMESTAMP NOT NULL DEFAULT DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 7 DAY),
    last_reminded DATE DEFAULT NULL
);

-- create structure for  'profileimg' table 
CREATE TABLE IF NOT EXISTS profileimg(
    username VARCHAR(30) NOT NULL PRIMARY KEY,
    status INT(11)
);