drop database if exists LyleIO;
create database LyleIO;
USE LyleIO;

drop table if exists Users;
CREATE TABLE Users (
	userName varchar(30) UNIQUE DEFAULT 'user',
	pass varchar(30) NOT NULL DEFAULT 'password',
	track varchar(20),
	email varchar(50) NOT NULL,
	userID int NOT NULL AUTO_INCREMENT,
	firstName varchar(30),
	lastName varchar(30),
	PRIMARY KEY (userID)
);

drop table if exists forums;
CREATE TABLE forums (
	forumID int NOT NULL AUTO_INCREMENT,
	forumName varchar(20) NOT NULL DEFAULT 'newForum',
	PRIMARY KEY (forumID)
);

drop table if exists Posts;
CREATE TABLE Posts (
	postID int NOT NULL AUTO_INCREMENT,
	title varchar(128) NOT NULL DEFAULT 'New Post',
	body varchar(1024) NOT NULL DEFAULT 'Body',
	forumID int NOT NULL,
	userID int NOT NULL,
	PRIMARY KEY (postID),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY(forumID) REFERENCES forums(forumID)
);

drop table if exists messages;
CREATE TABLE messages (
	msgID int NOT NULL AUTO_INCREMENT,
	postID int NOT NULL,
	body varchar(512) NOT NULL,
	userID int NOT NULL,
	PRIMARY KEY (msgID),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY(postID) REFERENCES Posts(postID)
);

