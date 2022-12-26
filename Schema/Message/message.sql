DELIMITER
$$
CRAETE PROCEDURE createMessage(IN chatId INT, IN sender VARCHAR(191), In messageStateId INT, IN text VARCHAR (2000))
BEGIN
INSERT INTO message (chatId, sender, messageStateId, text)
VALUES (chatId, sender, messageStateId, text);
END $$

DELIETER
$$
CREATE PROCEDURE markOwnerMessagesAsReceived(IN id INT)
BEGIN
UPDATE message m
SET m.messageStateId = (SELECT id FROM messageState WHERE name = 'RECEIVED') INNER JOIN chat c
ON m.chatId = c.id
    INNER JOIN owner o ON c.ownerId = o.id
WHERE o.id = id AND m.messageStateId = (SELECT id FROM messageState WHERE name = 'PENDING');
END $$
DELIMITER ;

DELIMITER
$$
CREATE PROCEDURE markKeeperMessagesAsReceived(IN id INT)
BEGIN
UPDATE m
SET m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'RECEIVED') FROM message m
    INNER JOIN chat c ON m.chatId = c.id
    INNER JOIN keeper k ON c.keeperId = k.id
WHERE k.id = id AND m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'PENDING');
END $$
DELIMITER ;



