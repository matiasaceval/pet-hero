SET GLOBAL event_scheduler=ON

DELIMITER $$
    CREATE EVENT `updateReservationState`
    ON SCHEDULE EVERY 4 HOUR
    STARTS CAST(curdate() AS Datetime)
    DO 
    BEGIN
        CALL verifyReservation();
    END$$
DELIMITER ;