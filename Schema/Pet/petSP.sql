DELIMITER
$$
CREATE PROCEDURE getAllPets()
BEGIN
SELECT *
FROM pet;
END $$
DELIMITER
CREATE PROCEDURE getPetById(IN id INT) DELIMITER $$
CREATE PROCEDURE getPetById(IN id INT)
BEGIN
SELECT *
FROM pet p
WHERE id = p.id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE getAllPetAndOwner()
BEGIN
SELECT p.*, o.firstname, o.lastname, o.email, o.phone, o.password
FROM pet p
         INNER JOIN owner o ON p.ownerId = o.id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE getPetAndOwnerById(IN id INT)
BEGIN
SELECT p.*, o.firstname, o.lastname, o.email, o.phone, o.password
FROM pet p
         INNER JOIN owner o ON p.ownerId = o.id
WHERE p.id = id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE addPet(IN name VARCHAR (191), IN species VARCHAR (191), IN breed VARCHAR (191),
                        IN sex VARCHAR (191), IN age VARCHAR (191), IN image VARCHAR (191), IN vaccine VARCHAR (191),
                        IN ownerId INT)
BEGIN
INSERT INTO pet (name, species, breed, sex, age, image, vaccines, ownerId)
VALUES (name, species, breed, sex, age, image, vaccine, ownerId);
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE updatePet(IN id INT, IN name VARCHAR (191), IN species VARCHAR (191), IN breed VARCHAR (191),
                           IN sex VARCHAR (191), IN age VARCHAR (191), IN image VARCHAR (191), IN vaccine VARCHAR (191),
                           IN ownerId INT)
BEGIN
UPDATE pet p
SET p.name     = name,
    p.species  = species,
    p.breed    = breed,
    p.sex      = sex,
    p.age      = age,
    p.image    = image,
    p.vaccines = vaccine,
    p.ownerId  = ownerId
WHERE p.id = id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE deletePet(IN id INT)
BEGIN
DELETE
p FROM pet p
WHERE id = p.id;
END $$
DELIMITER ;