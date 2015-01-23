#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------

DROP DATABASE IF EXISTS contact;

CREATE DATABASE contact
        DEFAULT CHARACTER SET utf8
        DEFAULT COLLATE utf8_general_ci;

USE contact;

CREATE TABLE Message(
        id_msg  int (11) Auto_increment  NOT NULL ,
        msg     Longtext ,
        id_user Int ,
        PRIMARY KEY (id_msg )
)ENGINE=InnoDB;


CREATE TABLE User(
        id_user int (11) Auto_increment  NOT NULL ,
        name    Varchar (50) ,
        mail    Varchar (50) ,
        PRIMARY KEY (id_user ) ,
        UNIQUE (name ,mail )
)ENGINE=InnoDB;


CREATE TABLE Info(
        id_mail   int (11) Auto_increment  NOT NULL ,
        mail_imie Varchar (50) ,
        PRIMARY KEY (id_mail )
)ENGINE=InnoDB;

ALTER TABLE Message ADD CONSTRAINT FK_Message_id_user FOREIGN KEY (id_user) REFERENCES User(id_user);

