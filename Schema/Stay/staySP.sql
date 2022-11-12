DELIMITER
$$
CREATE PROCEDURE `getAllStays`()
BEGIN
SELECT s.*, k.firstname, k.lastname, k.email, k.phone
FROM stay s
         INNER JOIN keeper k ON s.id = k.id;
END$$
DELIMITER ;


DELIMITER
$$
CREATE PROCEDURE `deleteStay`(IN stayId INT)
BEGIN
DELETE
s FROM stay s
WHERE s.id = stayId;
SELECT LAST_INSERT_ID();
END$$
DELIMITER ;


DELIMITER
$$
CREATE PROCEDURE `updateStay`(IN stayId INT, IN since DATE, IN until DATE)
BEGIN
UPDATE stay s
SET s.since = since,
    s.until = until
WHERE s.id = stayId;
SELECT s.* FROM stay s
WHERE s.id = stayId;
END$$
DELIMITER ;


-- if keeper doesn't have a stay, it will be created

DELIMITER
$$
CREATE PROCEDURE `addStay`(IN id INT, IN since DATE, IN until DATE)
BEGIN
INSERT INTO stay (id, since, until)
VALUES (id, since, until);
SELECT LAST_INSERT_ID();
END$$
DELIMITER ;
