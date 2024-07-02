CREATE TABLE Person(
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  fio varchar(150) NOT NULL DEFAULT '',
  phone varchar(12) NOT NULL DEFAULT '',
  mail varchar(100) NOT NULL DEFAULT '',
  bornday varchar(100) NOT NUll DEFAULT '',
  pol varchar(20) Not NULL DEFAULT '',
  biog varchar(300) NOT NULL DEFAULT '',
  V boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY(id)
);

CREATE TABLE Lang(
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  language varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY(id)
);

CREATE TABLE person_and_lang(
id int(10) unsigned NOT NULL AUTO_INCREMENT,
id_u int(10) unsigned Not NUll DEFAULT 0,
id_l int(10) unsigned Not NUll DEFAULT 0,
PRIMARY KEY(id)
);


CREATE TABLE Logi (
  login VARCHAR(255) NOT NULL UNIQUE PRIMARY KEY,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE LogPerson(
  login VARCHAR(255) NOT NULL references Logi(login),
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  fio varchar(150) NOT NULL DEFAULT '',
  phone varchar(12) NOT NULL DEFAULT '',
  mail varchar(100) NOT NULL DEFAULT '',
  birthdate varchar(100) NOT NUll DEFAULT '',
  pol varchar(20) Not NULL DEFAULT '',
  biog varchar(300) NOT NULL DEFAULT '',
  V boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY(id)
);

INSERT INTO Lang(language) VALUES ('C');
INSERT INTO Lang(language) VALUES ('Pascal');
INSERT INTO Lang(language) VALUES ('Scala');
INSERT INTO Lang(language) VALUES ('C++');
INSERT INTO Lang(language) VALUES ('Java');
INSERT INTO Lang(language) VALUES ('Python');
INSERT INTO Lang(language) VALUES ('JavaScript');
INSERT INTO Lang(language) VALUES ('PHP');
INSERT INTO Lang(language) VALUES ('Hascel');
INSERT INTO Lang(language) VALUES ('Clojure');
INSERT INTO Lang(language) VALUES ('Prolog');