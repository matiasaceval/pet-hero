DELIMITER
$$
CREATE PROCEDURE getAllPets()
BEGIN
SELECT *
FROM pet;
END $$
DELIMITER

DELIMITER
$$
CREATE PROCEDURE GetPetById(IN id INT)
BEGIN
SELECT p.id as petID, p.*, o.id as ownerId, o.*
FROM pet p
         INNER JOIN owner o ON p.ownerId = o.id
WHERE p.id = id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE getPetByOwnerId(IN id INT)
BEGIN
SELECT p.id as petID, p.*, o.id as ownerId, o.*
FROM pet p
         INNER JOIN owner o ON p.ownerId = o.id
WHERE id = o.id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE getAllPetAndOwner()
BEGIN
SELECT p.id as petID, p.*, o.id as ownerId, o.*
FROM pet p
         INNER JOIN owner o ON p.ownerId = o.id;
END $$
DELIMITER ;


DELIMITER
$$
CREATE PROCEDURE addPet(IN name VARCHAR (191), IN species VARCHAR (191), IN breed VARCHAR (191),
                        IN sex VARCHAR (191), IN age VARCHAR (191), IN image VARCHAR (191), IN vaccines VARCHAR (191),
                        IN ownerId INT)
BEGIN
INSERT INTO pet (name, species, breed, sex, age, image, vaccines, ownerId)
VALUES (name, species, breed, sex, age, image, vaccines, ownerId);
SELECT LAST_INSERT_ID();
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE updatePet(IN id INT, IN name VARCHAR (191), IN species VARCHAR (191), IN breed VARCHAR (191),
                           IN sex VARCHAR (191), IN age VARCHAR (191), IN image VARCHAR (191), IN vaccines VARCHAR (191),
                           IN ownerId INT, IN active BOOLEAN)
BEGIN
UPDATE pet p
SET p.name     = name,
    p.species  = species,
    p.breed    = breed,
    p.sex      = sex,
    p.age      = age,
    p.image    = image,
    p.vaccines = vaccines,
    p.ownerId  = ownerId,
    p.active   = active
WHERE p.id = id;
SELECT p.*, o.* FROM pet p
INNER JOIN owner o ON p.ownerId = o.id
WHERE p.id = id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE deletePet(IN id INT)
BEGIN
DELETE
p.* FROM pet p
WHERE id = p.id;
SELECT LAST_INSERT_ID();
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE disablePet(IN id INT)
BEGIN
UPDATE pet p
SET p.active = FALSE
WHERE p.id = id;
END $$
DELIMITER ;
