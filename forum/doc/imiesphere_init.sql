USE imisphere;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE profil;
TRUNCATE utilisateur;
TRUNCATE theme_forum;
TRUNCATE categorie_forum;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO profil (id_profil, libelle) VALUES (1, 'Administrateur'),
 												(2, 'Moderateur'),
												(3, 'Utilisateur'),
												(10, 'Visiteur');

INSERT INTO Utilisateur (pseudo, nom_utilisateur, prenom_utilisateur, password, email, id_profil) VALUES 
															('serge', 'coude', 'serge', MD5('coucou'), 'serge@imie.fr', 1),
															('denis', 'le gourierec', 'denis', MD5('toto'), 'denis@imie.fr', 3),
															('celia', 'renouf', 'celia', MD5('titi'), 'celia@imie.fr', 3),
															('sophie', 'badcop', 'sophie',  MD5('tata'), 'sophie@imie.fr', 10);

INSERT INTO theme_forum (libelle_theme, date_theme, id_utilisateur) VALUES
															('developpement', now(), 1),
															('systeme & reseau', now(), 1),
															('loisir', now(), 1);

INSERT INTO categorie_forum (libelle_categorie, date_categorie, id_theme, id_utilisateur) VALUES
															('C', now(), 1, 1),
															('Java', now(), 1, 1),
															('Web', now(), 1, 1),
															('Linux', now(), 2, 1),
															('Windows', now(), 2, 1),
															('Protocoles Reseaux', now(), 2, 1),
															('Soirees', now(), 3, 1),
															('Forum Bin', now(), 3, 1);

INSERT INTO topic_forum (libelle_topic, crea_topic, id_categorie, id_utilisateur) VALUES
															('Finch', now(), 1, 1),
															('Print', now(), 1, 1),
															('Hello World !', now(), 1, 1),
															('Mastermind', now(), 2, 1),
															('Ubuntu', now(), 4, 1),
															('Protocoles Reseaux', now(), 5, 1);

INSERT INTO message_forum (content_msg_forum, date_msg_forum, id_topic, id_utilisateur) VALUES
															('Bug led AARRRRRRGGGG', now(), 1, 1),
															('LOOSER', now(), 1, 1),
															('Les couleurs sont bizarres', now(), 4, 1),
															('Je me presente je mapelle henri...', now(), 5, 1);															
