DELIMITER $$
CREATE PROCEDURE getMessagesByChatId(IN chatId INT)
BEGIN
SELECT m.*,
       ms.state
FROM message m
INNER JOIN messageState ms ON m.messageStateId = ms.id
WHERE m.chatId = chatId
ORDER BY m.createdAt ASC;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE createMessage(IN chatId INT, IN content VARCHAR (2000), IN ownerIsSender TINYINT(1))
BEGIN
INSERT INTO message
VALUES (default, content, default, chatId, default, ownerIsSender);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE markOwnerMessagesAsReceived(IN ownerId INT)
BEGIN
UPDATE message m
INNER JOIN chat c ON m.chatId = c.reservationId
INNER JOIN owner o ON c.ownerId = o.id
SET m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'RECEIVED')
WHERE o.id = ownerId AND ownerIsSender = 1 AND m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'PENDING');
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE markKeeperMessagesAsReceived(IN id INT)
BEGIN
UPDATE message m
INNER JOIN chat c ON m.chatId = c.reservationId
INNER JOIN keeper k ON c.keeperId = k.id
SET m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'RECEIVED')
WHERE k.id = id AND ownerIsSender = 0 AND m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'PENDING');
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE markOwnerChatAsRead(IN ownerId INT, IN chatId INT)
BEGIN
UPDATE message m
INNER JOIN chat c ON m.chatId = c.reservationId
INNER JOIN owner o ON c.ownerId = o.id
SET m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'READ')
WHERE o.id = ownerId AND ownerIsSender = 1 AND c.reservationId = chatId;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE markKeeperChatAsRead(IN keeperId INT, IN chatId INT)
BEGIN
UPDATE message m
INNER JOIN chat c ON m.chatId = c.reservationId
INNER JOIN keeper k ON c.keeperId = k.id
SET m.messageStateId = (SELECT id FROM messageState ms WHERE ms.state = 'READ')
WHERE k.id = keeperId AND ownerIsSender = 0 AND c.reservationId = chatId;
END $$
DELIMITER ;