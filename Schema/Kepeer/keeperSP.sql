--stored procedure for clean code
DELIMITER $$
CREATE PROCEDURE getAllKeepers()
BEGIN
SELECT k.id as keeperId, k.*, s.since, s.until
FROM keeper k
         LEFT JOIN stay s ON k.id = s.id; -- left join to get all keepers even if they have no stay
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE getKeeperById(IN id INT)
BEGIN
SELECT k.id as keeperId, k.*, s.since, s.until
FROM keeper k
         INNER JOIN stay s ON k.id = s.id
WHERE k.id = id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE getKeeperByEmail(IN email VARCHAR (191) COLLATE utf8_unicode_ci)
BEGIN
SELECT k.id as keeperId, k.*, s.since, s.until
FROM keeper k
         INNER JOIN stay s ON k.id = s.id
WHERE k.email = email;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE addKeeper(IN firstname VARCHAR (191), IN lastname VARCHAR (191),
                           IN email VARCHAR (191) COLLATE utf8_unicode_ci, IN password VARCHAR (191),
                           IN phone VARCHAR (191), IN fee INT, IN since DATE, IN until DATE)
BEGIN
INSERT INTO keeper (firstname, lastname, email, password, phone, fee)
VALUES (firstname, lastname, email, password, phone, fee);
INSERT INTO stay (id, since, until)
VALUES ((SELECT k.id FROM keeper k WHERE k.email = email), since, until);
SELECT LAST_INSERT_ID();
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE updateKeeper(IN id INT, IN firstname VARCHAR (191), IN lastname VARCHAR (191),
                              IN email VARCHAR (191) COLLATE utf8_unicode_ci, IN password VARCHAR (191),
                              IN phone VARCHAR (191), IN fee INT, IN since DATE, IN until DATE)
BEGIN
UPDATE keeper k , stay s
SET k.firstname = firstname, k.lastname = lastname, k.email = email, k.password = password, k.phone = phone, k.fee = fee, s.since = since, s.until = until
WHERE k.id = id AND s.id = id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE deleteKeeper(IN id INT)
BEGIN
DELETE
k.* FROM keeper k
WHERE id = k.id;
END $$
DELIMITER ;

