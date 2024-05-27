CREATE DATABASE trial;

CREATE TABLE accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    accountid INT,
    email VARCHAR(255),
    password VARCHAR(255),
    FOREIGN KEY (accountid) REFERENCES users(userid)
);


CREATE TABLE users (
    userid INT PRIMARY KEY AUTO_INCREMENT,
    nameid INT,
    birthday DATE,
    age INT,
    location ENUM('Adams', 'Badoc', 'Bangui', 'Banna', 'Batac', 'Burgos', 'Carasi', 'Currimao', 'Dingras', 'Dumalneg', 'Laoag', 'Marcos', 'Nueva Era', 'Pagudpud', 'Paoay', 'Pasuquin', 'Piddig', 'Pinili', 'San Nicolas', 'Sarrat', 'Solsona'),
    gender ENUM('Male', 'Female'),
    preferences INT,
    profile_picture VARCHAR(255),
    background_photo VARCHAR(255),
    bio VARCHAR(255),
    FOREIGN KEY (nameid) REFERENCES username(nameid),
    FOREIGN KEY (preferences) REFERENCES preferences(prefid)
);


CREATE TABLE preferences (
    prefid INT PRIMARY KEY AUTO_INCREMENT,
    age_preference VARCHAR(10);
    gender_preference ENUM('Male', 'Female', 'Default') DEFAULT 'Default';
    preferred_location ENUM('Adams', 'Badoc', 'Bangui', 'Banna', 'Batac', 'Burgos', 'Carasi', 'Currimao', 'Dingras', 'Dumalneg', 'Laoag', 'Marcos', 'Nueva Era', 'Pagudpud', 'Paoay', 'Pasuquin', 'Piddig', 'Pinili', 'San Nicolas', 'Sarrat', 'Solsona', 'Default') DEFAULT 'Default';
    interest SET('Sports', 'Music', 'Movies', 'Books', 'Travel', 'Food', 'Gaming', 'Art', 'Fashion', 'Technology', 'Fitness', 'Other')
);

CREATE TABLE username (
    nameid INT PRIMARY KEY AUTO_INCREMENT,
    fname VARCHAR(50),
    mname VARCHAR(50),
    lname VARCHAR(50),
    nickname VARCHAR(50)
);

CREATE TABLE chatrooms (
    chatroom_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    sender_id INT(11),
    receiver_id INT(11),
    FOREIGN KEY (sender_id) REFERENCES users(userid),
    FOREIGN KEY (receiver_id) REFERENCES users(userid)
);

CREATE TABLE messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    chatroom_id INT(11),
    sender_id INT(11),
    message TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chatroom_id) REFERENCES chatrooms(chatroom_id)
);
ALTER TABLE messages ADD COLUMN status ENUM('active', 'deleted') DEFAULT 'active';




CREATE TABLE friends (
    friend_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id1 INT,
    user_id2 INT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id1) REFERENCES users(userid),
    FOREIGN KEY (user_id2) REFERENCES users(userid),
);

CREATE TABLE likes (
    like_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT,
    receiver_id INT,
    status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(userid),
    FOREIGN KEY (receiver_id) REFERENCES users(userid)
);



CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    recipient_id INT NOT NULL,
    sender_id INT NOT NULL,
    notification_text VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipient_id) REFERENCES users(userid),
    FOREIGN KEY (sender_id) REFERENCES users(userid)
);

