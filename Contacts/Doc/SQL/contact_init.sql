USE tchatprive;
-- On désactive la vérification des contraintes des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;
-- On vide les tables et les auto_increment sont remis à 0
TRUNCATE profil;
TRUNCATE Utilisateur;
-- On réactive la vérification des contraintes des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO info VALUES (default, "imiesphere@gmail.com");