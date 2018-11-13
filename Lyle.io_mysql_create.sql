drop database if exists LyleIO;
create database LyleIO;
USE LyleIO;

drop table if exists Users;
CREATE TABLE users (
	userName varchar(30) UNIQUE DEFAULT 'user',
	pass varchar(30) NOT NULL DEFAULT 'password',
	track varchar(20),
	email varchar(50) NOT NULL,
	userID int NOT NULL AUTO_INCREMENT,
	firstName varchar(30),
	lastName varchar(30),
	PRIMARY KEY (userID)
);
Insert into users (userName, pass, track, email, firstName, lastName) values ("Test","testpass","CSE", "test@test.net", "Test", "User");
Insert into users (userName, pass, track, email, firstName, lastName) values ("JSmith","JohnPass","Cyber Security", "JSmith@cyber.com", "John", "Smith");
Insert into users (userName, pass, track, email, firstName, lastName) values ("NSchweser","pw","Game Development", "Schweser@smu.edu", "Noah", "Schweser");

drop table if exists forums;
CREATE TABLE forums (
	forumID int NOT NULL AUTO_INCREMENT,
	forumName varchar(20) NOT NULL DEFAULT 'newForum',
	PRIMARY KEY (forumID)
);
Insert into forums (forumName) values ("CSE (General)");
Insert into forums (forumName) values ("Cyber Security");
Insert into forums (forumName) values ("Game Development");

drop table if exists Posts;
CREATE TABLE posts (
	postID int NOT NULL AUTO_INCREMENT,
	title varchar(128) NOT NULL DEFAULT 'New Post',
	body varchar(1024) NOT NULL DEFAULT 'Body',
	forumID int NOT NULL,
	userID int NOT NULL,
	PRIMARY KEY (postID),
	FOREIGN KEY (userID) REFERENCES users(userID),
	FOREIGN KEY(forumID) REFERENCES forums(forumID)
);
Insert into posts (title, body, forumID, userID) values ("Need help choosing a track","I dunno what track I want to be on and need advice on how to choose",1,1);
Insert into posts (title, body, forumID, userID) values ("Encryption","I need to figure out how to encrypt this data for homework",2,2);
Insert into posts (title, body, forumID, userID) values ("Best game engine?","Unity or Unreal? Please help me decide I am very poor",3,3);
Insert into posts (title, body, forumID, userID) values ("Come to Cyber Security Club!","It's gonna be great, I really think you'll enjoy it!",2,1);

drop table if exists messages;
CREATE TABLE messages (
	msgID int NOT NULL AUTO_INCREMENT,
	postID int NOT NULL,
	body varchar(512) NOT NULL,
	userID int NOT NULL,
	PRIMARY KEY (msgID),
	FOREIGN KEY (userID) REFERENCES users(userID),
	FOREIGN KEY(postID) REFERENCES posts(postID)
);
Insert into messages (postId, body, userID) values (1,"Try game development!",3);
Insert into messages (postId, body, userID) values (4,"Sounds fun! What time is it?",1);
Insert into messages (postId, body, userID) values (4,"It's at 6pm tomorrow in Caruth. Should be tons of fun!",2);
