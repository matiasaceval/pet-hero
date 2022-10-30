
DELIMITER $$
CREATE PROCEDURE getAllOwners ()
BEGIN
SELECT * FROM owner;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE getOwnerById (IN id INT)
BEGIN
SELECT * FROM owner o
WHERE id = o.id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE getOwnerByEmail (IN email VARCHAR(191) COLLATE utf8_unicode_ci)
BEGIN
SELECT * FROM owner o
WHERE email = o.email;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE addOwner (IN firstname VARCHAR(191),IN lastname VARCHAR(191), IN email VARCHAR(191) COLLATE utf8_unicode_ci , IN password VARCHAR(191),IN phone VARCHAR(191))
BEGIN 
INSERT INTO owner (firstname,lastname,email,password,phone) VALUES (firstname,lastname,email,password,phone);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE updateOwner (IN id INT, IN firstname VARCHAR(191),IN lastname VARCHAR(191), IN email VARCHAR(191) COLLATE utf8_unicode_ci, IN password VARCHAR(191),IN phone VARCHAR(191))
BEGIN
UPDATE owner o SET o.firstname = firstname, o.lastname = lastname, o.email = email, o.password = password, o.phone = phone
WHERE o.id = id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE  deleteOwner (IN id INT)
BEGIN
DELETE o FROM owner o 
WHERE id = o.id;
END $$
DELIMITER ;

