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