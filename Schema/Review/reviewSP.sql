DELIMITER
$$
CREATE PROCEDURE `addReview`(IN comment VARCHAR (191), IN rating INT, IN `date` DATE, IN petId INT, IN keeperId INT)
BEGIN
INSERT INTO review (comment, rating, `date`, petId, keeperId)
VALUES (comment, rating, `date`, petId, keeperId);
END $$
DELIMITER ;
    
DELIMITER
$$
CREATE PROCEDURE `getAllReviews`()
BEGIN
SELECT r.*,
       k.firstname,
       k.lastname,
       k.email,
       k.phone,
       p.name,
       p.species,
       p.breed,
       p.sex,
       p.age,
       p.image,
       p.vaccines
FROM review r
         INNER JOIN keeper k ON r.keeperId = k.id
         INNER JOIN pet p ON r.petId = p.id;
END$$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE `deleteReview`(IN reviewId INT)
BEGIN
DELETE
r FROM review r
WHERE r.id = reviewId;
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
END$$
DELIMITER ;