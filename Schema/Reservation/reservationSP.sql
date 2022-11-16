DELIMITER
$$
CREATE PROCEDURE getAllReservations()
BEGIN
SELECT r.*, p.*, k.*
FROM reservation r
         INNER JOIN pet p ON r.petId = p.id
         INNER JOIN owner k ON p.ownerId = k.id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `getReservationById`(IN `id` INT)
BEGIN
SELECT r.*, p.*, k.*
FROM reservation r
         INNER JOIN pet p ON r.petId = p.id
         INNER JOIN keeper k ON r.keeperId = k.id
WHERE `id` = r.id;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `getReservationByState`(IN state VARCHAR (191) COLLATE utf8_unicode_ci)
BEGIN
SELECT r.*, p.*, k.*
FROM `reservation` r
         INNER JOIN pet p ON r.petId = p.id
         INNER JOIN keeper k ON r.keeperId = k.id
WHERE r.state = state;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `getReservationByPetId`(IN `petId` INT)
BEGIN
SELECT r.*, p.*, k.*
FROM `reservation` r
         INNER JOIN pet p ON r.petId = p.id
         INNER JOIN keeper k ON r.keeperId = k.id
WHERE `petId` = r.petId;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `getReservationByKeeperId`(IN `keeperId` INT)
BEGIN
SELECT r.*, p.*, k.*
FROM `reservation` r
         INNER JOIN pet p ON r.petId = p.id
         INNER JOIN keeper k ON r.keeperId = k.id
WHERE `keeperId` = r.keeperId;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `getReservationByOwnerId`(IN `ownerId` INT)
BEGIN
SELECT r.*, p.*, k.*
FROM `reservation` r
         INNER JOIN pet p ON r.petId = p.id
         INNER JOIN keeper k ON r.keeperId = k.id
WHERE `ownerId` = p.ownerId;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `deleteReservation`(IN id INT)
BEGIN
DELETE
r FROM reservation r
WHERE r.id = id;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `updateReservationState`(IN id INT, IN state VARCHAR (191), IN payment VARCHAR (191))
BEGIN
UPDATE reservation r
SET r.state   = state,
    r.payment = payment
WHERE r.id = id;
END$$
DELIMITER ;


DELIMITER
$$
CREATE PROCEDURE `addReservation`(IN petId INT, IN keeperId INT, IN state VARCHAR (191), IN since DATE, IN until DATE,
                                  price FLOAT (10), IN payment VARCHAR (191))
BEGIN
INSERT INTO reservation (petId, keeperId, state, since, until, price, payment)
VALUES (petId, keeperId, state, since, until, price, payment);
SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER
$$
create procedure `verifyReservation`()
BEGIN

SET
@date = CURDATE();
UPDATE reservation r

SET r.state = CASE
                  WHEN r.state = 'PENDING' AND r.since < @date THEN 'CANCELED'
                  WHEN r.state = 'CONFIRMED' AND r.since < @date THEN 'CANCELED'
                  WHEN r.state = 'PAID' AND r.since = @date THEN 'IN_PROGRESS'
                  WHEN r.state = 'IN_PROGRESS' AND r.until < @date THEN 'FINISHED'
                  ELSE r.state
    END;
END$$
DELIMITER ;