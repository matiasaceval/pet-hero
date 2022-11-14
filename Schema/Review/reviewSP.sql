DELIMITER
$$
CREATE PROCEDURE `addReview`(IN comment VARCHAR (191), IN rating INT, IN `date` DATE, IN reservationId INT)
BEGIN
INSERT INTO review (comment, rating, `date`, reservationId)
VALUES (comment, rating, `date`, reservationId);
SELECT LAST_INSERT_ID();
END $$
DELIMITER ;
    
DELIMITER
$$
CREATE PROCEDURE `getAllReviews`()
BEGIN
SELECT r.*, k.*, p.*, o.*, s.*, re.*
FROM review r
         INNER JOIN reservation re ON re.id = r.reservationId
         INNER JOIN keeper k ON k.id = re.keeperId
         LEFT JOIN pet p ON p.id = re.petId
         INNER JOIN owner o ON o.id = p.ownerId
         INNER JOIN stay s ON s.id = k.id END$$
DELIMITER;

DELIMITER
$$
CREATE PROCEDURE getReviewByKeeperId(IN id INT)
BEGIN
SELECT r.*, k.*, p.*, o.*, s.*, re.*
FROM review r
         INNER JOIN reservation re ON re.id = r.reservationId
         INNER JOIN keeper k ON k.id = re.keeperId
         LEFT JOIN pet p ON p.id = re.petId
         INNER JOIN owner o ON o.id = p.ownerId
         INNER JOIN stay s ON s.id = k.id
WHERE r.keeperId = id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE getReviewById(IN id INT)
BEGIN
SELECT r.*, k.*, p.*, o.*, s.*, re.*
FROM review r
         INNER JOIN reservation re ON re.id = r.reservationId
         INNER JOIN keeper k ON k.id = re.keeperId
         LEFT JOIN pet p ON p.id = re.petId
         INNER JOIN owner o ON o.id = p.ownerId
         INNER JOIN stay s ON s.id = k.id
WHERE r.id = id;
END $$

DELIMITER
$$
CREATE PROCEDURE getReviewByOwnerId(IN id INT)
BEGIN
SELECT r.*, k.*, p.*, o.*, s.*, re.*
FROM review r
         INNER JOIN reservation re ON re.id = r.reservationId
         INNER JOIN keeper k ON k.id = re.keeperId
         LEFT JOIN pet p ON p.id = re.petId
         INNER JOIN owner o ON o.id = p.ownerId
         INNER JOIN stay s ON s.id = k.id
WHERE o.id = id;
END $$

DELIMITER
$$
CREATE PROCEDURE getReviewByReservationId(IN id INT)
BEGIN
SELECT r.*, k.*, p.*, o.*, s.*, re.*
FROM review r
         INNER JOIN reservation re ON re.id = r.reservationId
         INNER JOIN keeper k ON k.id = re.keeperId
         LEFT JOIN pet p ON p.id = re.petId
         INNER JOIN owner o ON o.id = p.ownerId
         INNER JOIN stay s ON s.id = k.id
WHERE r.reservationId = id;
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `deleteReview`(IN reviewId INT)
BEGIN
DELETE
r FROM review r
WHERE r.id = reviewId;
SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `updateReview`(IN reviewId INT, IN comment VARCHAR (191), IN rating INT)
BEGIN
UPDATE review r
SET r.comment = comment,
    r.rating  = rating
WHERE r.id = reviewId;
SELECT r.*
FROM review r
WHERE r.id = reviewId;
END$$
DELIMITER ;