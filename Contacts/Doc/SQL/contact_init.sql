USE contact;

-- On désactive la vérification des contraintes des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- On réactive la vérification des contraintes des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO info VALUES (default, "imiesphere@gmail.com", "02 23 44 69 00", "Rue Pierre de Maupertuis", "Campus de Ker Lann", "Immeuble Alliance", "35170 Bruz");