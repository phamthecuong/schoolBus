DROP TRIGGER IF EXISTS after_student_trips_update;
DELIMITER ;;
CREATE TRIGGER after_student_trips_update

 AFTER UPDATE ON student_trips

 FOR EACH ROW

BEGIN
IF (OLD.status != NEW.status) THEN
INSERT INTO trip_logs
SET
	 trip_id = OLD.trip_id,
     student_id = OLD.student_id,
     status = NEW.status,
     updated_at = NOW();
END IF;
END ;;
DELIMITER ;