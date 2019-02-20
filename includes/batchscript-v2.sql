----------------------

START TRANSACTION;

INSERT INTO standings (userid, mid, mpoints) 
SELECT p.userid, p.matchid, 10
FROM predictions as p
INNER JOIN results as r ON p.matchid=r.mid AND p.teamid=r.mresult
LEFT JOIN standings AS s ON s.mid=p.matchid AND s.userid=p.userid
WHERE s.mid IS NULL;

INSERT INTO standings (userid, mid, mpoints) 
SELECT p.userid, p.matchid, 0
FROM predictions as p
INNER JOIN results as r ON p.matchid=r.mid AND p.teamid!=r.mresult
LEFT JOIN standings AS s ON s.mid=p.matchid AND s.userid=p.userid
WHERE s.mid IS NULL;

UPDATE standings AS s
INNER JOIN results AS r ON s.mid=r.mid
INNER JOIN predictions AS p ON p.userid = s.userid AND p.matchid=r.mid AND p.answer=3
SET s.gpoints = 0
WHERE s.gpoints IS NULL;

UPDATE standings AS s
INNER JOIN matches AS m ON s.mid=m.id AND m.jackpot=1
INNER JOIN results AS r ON s.mid=r.mid
INNER JOIN predictions AS p ON p.userid = s.userid AND p.matchid=r.mid and p.answer=r.qresult
SET s.gpoints = 20
WHERE s.gpoints IS NULL;

UPDATE standings AS s
INNER JOIN matches AS m ON s.mid=m.id AND m.jackpot=0
INNER JOIN results as r ON s.mid=r.mid
INNER JOIN predictions AS p ON p.userid = s.userid AND p.matchid=r.mid and p.answer=r.qresult
SET s.gpoints = 5
WHERE s.gpoints IS NULL;

UPDATE standings AS s
INNER JOIN matches AS m ON s.mid=m.id AND m.jackpot=0
INNER JOIN results as r ON s.mid=r.mid
INNER JOIN predictions AS p ON p.userid = s.userid AND p.matchid=r.mid AND p.answer!=r.qresult AND p.answer!=3
SET s.gpoints = -5
WHERE s.gpoints IS NULL;

UPDATE standings AS s
INNER JOIN matches AS m ON s.mid=m.id AND m.jackpot=1
INNER JOIN results as r ON s.mid=r.mid
INNER JOIN predictions AS p ON p.userid = s.userid AND p.matchid=r.mid AND p.answer!=r.qresult AND p.answer!=3
SET s.gpoints = -20
WHERE s.gpoints IS NULL;

UPDATE standings SET total=gpoints+mpoints WHERE total IS NULL;

UPDATE standings SET wpoints=total WHERE wpoints IS NULL;

COMMIT;


DELIMITER //
CREATE PROCEDURE `sp_amtcalc` (IN p_week INT)
BEGIN
    DECLARE b, totalrecords,reminder,green,blue,red,rcount INT;
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
    SET green = (totalrecords-reminder)/3;
    SET blue = green + (totalrecords-reminder)/3;
	SET red = totalrecords;
    
    WHILE b = 0 DO
        FETCH cur1 INTO userid,week,score;
        IF rcount <= green THEN
            INSERT INTO amount VALUES (userid,week,0, 0);
		ELSEIF rcount <= blue THEN
            INSERT INTO amount VALUES (userid,week,30, 0);
		ELSEIF rcount <= red THEN
            INSERT INTO amount VALUES (userid,week,50, 0);
    	END IF;
	SET rcount = rcount + 1;
    END WHILE;
 
    CLOSE cur1;
 
END //

CALL sp_amtcalc(3);

SELECT uv.userid, COUNT(uv.matchid)
FROM uvotes AS uv
INNER JOIN matches as m ON uv.matchid = m.id AND m.week=2
GROUP BY uv.userid

-----------------total users who have played googly on specified week
SELECT ua.userid, COUNT(ua.answer)
FROM uanswers AS ua
INNER JOIN questions AS q ON q.id = ua.qid
INNER JOIN matches AS m ON m.id=q.matchid
WHERE m.week=5
GROUP BY ua.userid

SELECT uv.userid, COUNT(uv.predictedteam)
FROM uvotes AS uv
INNER JOIN matches AS m ON m.id = uv.matchid
WHERE m.week=5
GROUP BY uv.userid

--------------users not played matches
SELECT * FROM users AS u1 
WHERE u1.id NOT IN (
SELECT DISTINCT(u.id) as userid
FROM users AS u
INNER JOIN uvotes AS uv ON uv.userid = u.id
INNER JOIN matches AS m ON m.id = uv.matchid
WHERE m.week=5
)


--calculate amount contributed by each member
SELECT u.name as Username, SUM(a.amount) as TotalAmount
FROM amount AS a 
INNER JOIN users as u ON a.userid=u.id
GROUP BY Username
ORDER BY TotalAmount DESC

INSERT INTO matches 
VALUES 
(NULL, '8', '2', '2017-04-06', '3', '3', '0', '1', 'Will the team winning the toss go on to win the match?', '0'),
(NULL, '3', '4', '2017-04-07', '6', '3', '0', '1', 'Will the opening bowler concede more than 25 runs in his allotted overs?', '0'),
(NULL, '6', '8', '2017-04-08', '12', '2', '0', '1', 'Will the 1 down batsman score less than 20 runs?', '0'),
(NULL, '5', '1', '2017-04-08', '9', '3', '0', '1', 'Will the target score be 170 or more?', '0'),
(NULL, '7', '3', '2017-04-09', '4', '2', '0', '1', 'Will the team batting first score more runs in Powerplay than the other?', '0'),
(NULL, '2', '4', '2017-04-09', '1', '3', '0', '1', 'Will the aggregate runs scored be 300 or more?', '0'),
(NULL, '6', '5', '2017-04-10', '12', '3', '0', '1', 'Will any opener score 35 or more runs?', '0'),
(NULL, '8', '1', '2017-04-11', '3', '3', '0', '1', 'Will any batsmen hit 3 or more sixes?', '0')

--update visible
UPDATE matches SET visible=0 WHERE week=1

--

SELECT * FROM
(SELECT id FROM teams WHERE shortname='SRH') AS t1,
(SELECT id FROM teams WHERE shortname='DD') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='KXIP') AS t1,
(SELECT id FROM teams WHERE shortname='MI') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='KKR') AS t1,
(SELECT id FROM teams WHERE shortname='GL') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='RPS') AS t1,
(SELECT id FROM teams WHERE shortname='SRH') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='MI') AS t1,
(SELECT id FROM teams WHERE shortname='DD') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='GL') AS t1,
(SELECT id FROM teams WHERE shortname='KXIP') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='KKR') AS t1,
(SELECT id FROM teams WHERE shortname='RCB') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='MI') AS t1,
(SELECT id FROM teams WHERE shortname='RPS') AS t2
UNION ALL
SELECT * FROM
(SELECT id FROM teams WHERE shortname='RCB') AS t1,
(SELECT id FROM teams WHERE shortname='SRH') AS t2


INSERT INTO matches
VALUES 
(NULL, '7', '1', '2017-04-19', '4', '3', '0', '3', 'Will the team batting first score more runs in Powerplay than the other?', '0'),
(NULL, '6', '2', '2017-04-20', '12', '3', '0', '3', 'Will any team be all out?', '0'),
(NULL, '4', '3', '2017-04-21', '8', '3', '0', '3', 'Will any bowler take 3 or more wickets?', '0'),
(NULL, '8', '7', '2017-04-22', '3', '2', '0', '3', 'Will the aggregate runs scored by both team be 300 or more?', '0'),
(NULL, '2', '1', '2017-04-22', '1', '3', '0', '3', 'Will the team batting first score more runs in Powerplay than the other?', '0'),
(NULL, '3', '6', '2017-04-23', '6', '2', '0', '3', 'Will there be any free hit available in entire match?', '0'),
(NULL, '4', '5', '2017-04-23', '8', '3', '0', '3', 'Will any bowler get wicket(s) in his first over?', '0'),
(NULL, '2', '8', '2017-04-24', '1', '3', '0', '3', 'Will the Captain score 40+ runs?', '0'),
(NULL, '5', '7', '2017-04-25', '9', '3', '0', '3', 'Will spinners from both teams pick more wickets than pace bowlers?', '0')


--predictions not submitted
SELECT u.fname, u.lname, u.email FROM users AS u
WHERE u.id NOT IN
(SELECT p.userid FROM predictions AS p
INNER JOIN
(SELECT id FROM matches WHERE week=3) AS m ON p.matchid=m.id)

SELECT u.fname, u.lname, u.email
FROM predictions AS p
INNER JOIN matches AS m ON p.matchid=m.id AND m.week=3
RIGHT JOIN users AS u ON u.id=p.userid
WHERE p.matchid IS null

SELECT u.fname, u.lname, u.email
FROM (SELECT id FROM matches WHERE week=3) AS m
INNER JOIN predictions AS p
ON p.matchid=m.id
RIGHT JOIN users AS u ON p.userid=u.id
WHERE p.matchid IS null