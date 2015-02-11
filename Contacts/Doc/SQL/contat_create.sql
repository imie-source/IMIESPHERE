#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------

DROP DATABASE IF EXISTS contact;

CREATE DATABASE contact;

USE contact;

CREATE TABLE Contact(
        id_contact int (11) Auto_increment  NOT NULL ,
        nom        Varchar (50) NOT NULL,
        mail       Varchar (50) NOT NULL,
        tel        Varchar (20) NOT NULL,
        objet	   Varchar (50) NOT NULL,
        msg        Longtext NOT NULL,
        PRIMARY KEY (id_contact )
)ENGINE=InnoDB;


CREATE TABLE Info(
        id_info   int (11) Auto_increment  NOT NULL ,
        mail_imie Varchar (50) NOT NULL ,
        tel_imie Varchar (25) NOT NULL ,
        adresse1_imie Varchar (50) NOT NULL ,
        adresse2_imie Varchar (50) NOT NULL ,
        adresse3_imie Varchar (50) NOT NULL ,
        adresse4_imie Varchar (50) NOT NULL ,
        PRIMARY KEY (id_info )
)ENGINE=InnoDB;

GRANT ALL PRIVILEGES ON contact.* TO 'contactuser'@'localhost' IDENTIFIED BY 'contact';