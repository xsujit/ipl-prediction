DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_amtcalc`(IN `p_week` INT)
BEGIN
    DECLARE b, totalrecords,reminder,green,amber,red,rcount INT;
    DECLARE userid,week,score INT;
    DECLARE cur1 CURSOR FOR SELECT s.userid , m.week , SUM(s.wpoints) as wscore
								FROM standings AS s
								INNER JOIN matches AS m ON m.id=s.mid AND m.week=p_week
								GROUP BY s.userid
								ORDER BY wscore DESC;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET b = 1;
    OPEN cur1;
 
    SET b = 0;
    SET rcount = 1;
    
	select FOUND_ROWS() into totalrecords ;
    SET reminder = MOD(totalrecords, 3);
    SET green = FLOOR(totalrecords/3);
    SET amber = green;
    SET red = green;
    
    IF reminder = 1 THEN
    	SET amber = amber + green;
        SET red= red + amber + 1;
    ELSEIF reminder = 2 THEN
    	SET amber = amber + green + 1;
        SET red = red + amber + 1;
    ELSE
    	SET amber = amber + green;
        SET red=red+amber;
  	END IF;
    
    WHILE b = 0 DO
        FETCH cur1 INTO userid,week,score;
        IF rcount <= green THEN
            INSERT INTO amount VALUES (userid,week,0, 2);
		ELSEIF rcount <= amber THEN
            INSERT INTO amount VALUES (userid,week,25, 0);
		ELSEIF rcount <= red THEN
            INSERT INTO amount VALUES (userid,week,50, 0);
    	END IF;
	SET rcount = rcount + 1;
    END WHILE;
 
    CLOSE cur1;
 
END$$
DELIMITER ;
