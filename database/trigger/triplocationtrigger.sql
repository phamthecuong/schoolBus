DROP TRIGGER IF EXISTS after_trip_locations_insert;
DELIMITER ;;
CREATE TRIGGER after_trip_locations_insert

 AFTER INSERT ON trip_locations

 FOR EACH ROW

BEGIN

UPDATE trips
SET
	 lat = NEW.lat,
	 trips.long = NEW.long,
	 updated_at = NOW()
WHERE id = NEW.trip_id;

END ;;
DELIMITER ;