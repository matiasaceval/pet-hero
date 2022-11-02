
mysql> CREATE EVENTS `updateReservationState` 
ON SCHEDULE 
EVERY 1 DAY
DO CALL verifyReservation();