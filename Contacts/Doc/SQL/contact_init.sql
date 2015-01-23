USE contact;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE profil;
TRUNCATE Utilisateur;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO User (name, mail) VALUES 
		("Serge", "serge@imie.fr"),
		("Denis", "denis@imie.fr"),
		("Benoit", "benoit@imie.fr");
