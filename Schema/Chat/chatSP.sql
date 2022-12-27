DELIMITER $$
CREATE PROCEDURE getChatById(IN id INT)
BEGIN
SELECT c.*,
       k.firstname as keeperFirstname,
       k.lastname  as keeperLastname,
       k.id        as keeperId,
       o.firstname as ownerFirstname,
       o.lastname  as ownerLastname,
       o.id        as ownerId
FROM chat c
         INNER JOIN keeper k ON c.keeperId = k.id
         INNER JOIN owner o ON c.ownerId = o.id
WHERE c.reservationId = id;
END $$
DELIMITER ;