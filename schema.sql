CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(128),
  users_id INT
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  createDate DATETIME,
  doneDate DATETIME,
  title CHAR(128),
  file CHAR(128),
  deadline DATETIME,
  users_id INT,
  projects_id INT
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64),
  email CHAR(128),
  password CHAR(64),
  regDate DATETIME,
  contacts CHAR(128)
);