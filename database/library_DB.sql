-- table structure for table 'member'
CREATE TABLE IF NOT EXISTS member (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd CHAR(40) NOT NULL, 
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
)

-- table structure for table pending_registrations'
CREATE TABLE IF NOT EXISTS pending_registrations (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd CHAR(40) NOT NULL, 
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
)

-- table structure for table "librarian"
CREATE TABLE IF NOT EXISTS librarian(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    pwd CHAR(40) NOT NULL
)

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
)