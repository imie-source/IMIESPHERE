DROP DATABASE IF EXISTS imiesphere;

CREATE DATABASE imiesphere
        DEFAULT CHARACTER SET utf8
        DEFAULT COLLATE utf8_general_ci;

USE imiesphere;


CREATE TABLE utilisateur(
        id_utilisateur     int (11) Auto_increment  NOT NULL ,
        pseudo             Varchar (25) ,
        nom_utilisateur    Varchar (50) NOT NULL ,
        prenom_utilisateur Varchar (50) NOT NULL ,
        password           Varchar (50) ,
        email              Varchar (25) ,
        id_profil          Int ,
        PRIMARY KEY (id_utilisateur )
)ENGINE=InnoDB;


CREATE TABLE profil(
        id_profil int (11) Auto_increment  NOT NULL ,
        libelle   Varchar (25) ,
        PRIMARY KEY (id_profil )
)ENGINE=InnoDB;


CREATE TABLE topic_forum(
        id_topic       int (11) Auto_increment  NOT NULL ,
        libelle_topic  Varchar (50) NOT NULL ,
        crea_topic     Date NOT NULL ,
        id_utilisateur Int ,
        id_categorie   Int NOT NULL ,
        PRIMARY KEY (id_topic )
)ENGINE=InnoDB;


CREATE TABLE article(
        id_article      int (11) Auto_increment  NOT NULL ,
        title_article   Varchar (50) NOT NULL ,
        content_article Longtext NOT NULL ,
        publication_article Datetime NOT NULL,
        edition_article Datetime NOT NULL,
        id_utilisateur  Int ,
        PRIMARY KEY (id_article )
)ENGINE=InnoDB;


CREATE TABLE message_tchat(
        id_msg_tchat      int (11) Auto_increment  NOT NULL ,
        date_msg_tchat    Datetime NOT NULL ,
        content_msg_tchat Longtext NOT NULL ,
        id_utilisateur    Int ,
        PRIMARY KEY (id_msg_tchat )
)ENGINE=InnoDB;


CREATE TABLE message_forum(
        id_msg_forum      int (11) Auto_increment  NOT NULL ,
        date_msg_forum    Datetime NOT NULL ,
        content_msg_forum Longtext NOT NULL ,
        id_utilisateur    Int ,
        id_topic          Int NOT NULL ,
        PRIMARY KEY (id_msg_forum )
)ENGINE=InnoDB;


CREATE TABLE categorie_forum(
        id_categorie      int (11) Auto_increment  NOT NULL ,
        libelle_categorie Varchar (50) NOT NULL ,
        date_categorie    Date NOT NULL ,
        id_theme          Int NOT NULL ,
        id_utilisateur    Int ,
        PRIMARY KEY (id_categorie )
)ENGINE=InnoDB;


CREATE TABLE theme_forum(
        id_theme       int (11) Auto_increment  NOT NULL ,
        libelle_theme  Varchar (50) NOT NULL ,
        date_theme     Date NOT NULL ,
        id_utilisateur Int ,
        PRIMARY KEY (id_theme )
)ENGINE=InnoDB;

CREATE TABLE Contact(
        id_contact int (11) Auto_increment  NOT NULL ,
        nom        Varchar (50) NOT NULL,
        mail       Varchar (50) NOT NULL,
        tel        Varchar (20) NOT NULL,
        objet      Varchar (50) NOT NULL,
        msg        Longtext NOT NULL,
        PRIMARY KEY (id_contact )
)ENGINE=InnoDB;

CREATE TABLE informations(
        id_info       int (11) Auto_increment  NOT NULL ,
        adresse_email Varchar (75) NOT NULL ,
        telephone     Varchar (25) NOT NULL ,
        adresse_rue   Varchar (50) NOT NULL ,
        adresse_campus Varchar (25) NOT NULL ,
        adresse_build Varchar (25) NOT NULL ,
        adresse_ville Varchar (25) NOT NULL ,
        PRIMARY KEY (id_info )
)ENGINE=InnoDB;


CREATE TABLE projet(
        id_projet         int (11) Auto_increment  NOT NULL ,
        nom_projet        Varchar (50) NOT NULL ,
        date_debut_projet Date ,
        duree_projet      Int NOT NULL ,
        classe_projet     Varchar (25) ,
        groupe_eleve      Varchar (200) ,
        contenu_projet    Longtext NOT NULL ,
        PRIMARY KEY (id_projet )
)ENGINE=InnoDB;

ALTER TABLE utilisateur ADD CONSTRAINT FK_utilisateur_id_profil FOREIGN KEY (id_profil) REFERENCES profil(id_profil);
ALTER TABLE topic_forum ADD CONSTRAINT FK_topic_forum_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE topic_forum ADD CONSTRAINT FK_topic_forum_id_categorie FOREIGN KEY (id_categorie) REFERENCES categorie_forum(id_categorie);
ALTER TABLE article ADD CONSTRAINT FK_article_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE message_tchat ADD CONSTRAINT FK_message_tchat_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE message_forum ADD CONSTRAINT FK_message_forum_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE message_forum ADD CONSTRAINT FK_message_forum_id_topic FOREIGN KEY (id_topic) REFERENCES topic_forum(id_topic);
ALTER TABLE message_blog ADD CONSTRAINT FK_message_blog_id_article FOREIGN KEY (id_article) REFERENCES article(id_article);
ALTER TABLE message_blog ADD CONSTRAINT FK_message_blog_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE categorie_forum ADD CONSTRAINT FK_categorie_forum_id_theme FOREIGN KEY (id_theme) REFERENCES theme_forum(id_theme);
ALTER TABLE categorie_forum ADD CONSTRAINT FK_categorie_forum_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE theme_forum ADD CONSTRAINT FK_theme_forum_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur);

GRANT ALL PRIVILEGES ON imiesphere.* TO 'admin'@'localhost'  IDENTIFIED BY 'administrator';