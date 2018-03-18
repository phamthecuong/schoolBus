DROP TRIGGER IF EXISTS after_trip_departures_update;
DELIMITER ;;
CREATE TRIGGER after_trip_departures_update

 AFTER UPDATE ON trip_departures

 FOR EACH ROW

BEGIN
	DECLARE result INT DEFAULT 0;
/*IF (NEW.finish_at != null) THEN*/
	SELECT COUNT(*) INTO result FROM trip_departures td
	WHERE trip_id = OLD.trip_id AND td.finish_at IS NULL
	GROUP BY trip_id;
IF (result < 1) THEN
	UPDATE trips
	SET
		 finish_at = NOW()
	WHERE id = OLD.trip_id;


END IF;
END ;;
DELIMITER ;