<?php 
require "function.php";

$grade = isset($_GET['grade']) ? $_GET['grade'] : 0;
$section = isset($_GET['section']) ? $_GET['section'] : 0;
$school_year = isset($_GET['school_year']) ? $_GET['school_year'] : 0;
$student = isset($_GET['student']) ? $_GET['student'] : 0;
$enroll = isset($_GET['enroll']) ? $_GET['enroll'] : 0;
$principal = isset($_GET['principal']) ? $_GET['principal'] : 2 	;
$teacher = isset($_GET['teacher']) ? $_GET['teacher'] : 1;


$data = [

    'principalcurrentnumber' => [
            'display' => [
                'query' => "SELECT 
                                en.en_grade,
                                COUNT(1) AS number,
                                en.en_shoolyear
                            FROM 
                                `users` us
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            LEFT JOIN 
                                `users` ust ON en.en_teID = ust.us_id
                            WHERE 
                                en.en_shoolyear = (
                                    SELECT MAX(`ev_title`) 
                                    FROM `events`
                                    WHERE 
                                        `ev_assign` = 'SY' 
                                        AND `ev_status` = 'Accepted'
                                        AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                                        AND (LEFT(`ev_title`, 4) = YEAR(CURDATE()) 
                                            OR RIGHT(`ev_title`, 4) = YEAR(CURDATE()) + 1)
                                ) AND ?
                                GROUP BY  
                                    en.en_shoolyear, 
                                    en.en_grade
                            ORDER BY 
                                en.en_grade ASC;
                                ", 
                'bind'  => "i",
               'value' => [1]
            ], 
            
            
            'upcoming' => [
               'query' => "SELECT 
                                `ev_title`, 
                                `ev_description`,
                                CASE 
                                    WHEN ev.ev_sdate = ev.ev_edate THEN DATE_FORMAT(ev.ev_sdate, '%b %d, %Y')  
                                    ELSE CONCAT(DATE_FORMAT(ev.ev_sdate, '%b %d, %Y'), ' - ', DATE_FORMAT(ev.ev_edate, '%b %d, %Y'))
                                END AS `date`
                            FROM 
                                `events` ev
                            INNER JOIN 
                                users us 
                                ON ev.ev_usID = us.us_id 
                                AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian')
                            WHERE 
                                ev_status = ?  AND ev.`ev_assign` != 'SY' 
                                AND ev_sdate > NOW()
                                ORDER BY ev.ev_sdate ASC, ev.ev_stime DESC LIMIT 5", 
                            
               'bind'  => "s",
               'value' => ['Accepted']
            ], 

            'name' => [
               'query' => "SELECT CONCAT(us_lname, ', ', us_fname, ', ', us_mname) AS name
                           FROM `users` WHERE us_id = ?  AND us_type = 'principal'",           
               'bind'  => "i",
               'value' => [$principal]
            ], 
    ],



   'principalcurrentclasses' => [
            'display' => [
                'query' => "SELECT 
                                en.en_teID,
                                CASE WHEN CONCAT(ust.us_lname, ', ', ust.us_fname, ', ', ust.us_mname)  IS NULL THEN 'none' 
                                     ELSE CONCAT(ust.us_lname, ', ', ust.us_fname, ', ', ust.us_mname) 
                                END AS name,
                                en.en_section, 
                                en.en_shoolyear, 
                                en.en_grade,
                                COUNT(1) AS number
                            FROM 
                                `users` us
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            LEFT JOIN 
                                `users` ust ON en.en_teID = ust.us_id
                            WHERE 
                                en.en_shoolyear = (
                                     SELECT MAX(`ev_title`) 
                                     FROM `events`
                                     WHERE `ev_assign` = 'SY' 
                                     AND `ev_status` = 'Accepted'
                                     AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                                     AND (LEFT(`ev_title`, 4) = YEAR(CURDATE()) OR RIGHT(`ev_title`, 4) = YEAR(CURDATE()) + 1)
                                )
                            AND 
                               ?
                            GROUP BY  
                                en.en_section, en.en_shoolyear, en.en_grade;", 
                'bind'  => "i",
               'value' => [1]
            ],

            
            'gradelevel' => [
                'query' => "SELECT DISTINCT

                                en.en_grade
                                
                            FROM 
                                `users` us
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            LEFT JOIN 
                                `users` ust ON en.en_teID = ust.us_id
                            WHERE 
                                en.en_shoolyear = (
                                    SELECT MAX(en1.en_shoolyear) 
                                    FROM `enrollments` en1
                                )
                            AND 
                                ? 
                            GROUP BY  
                                en.en_section, en.en_shoolyear, en.en_grade;", 
                'bind'  => "i",
               'value' => [1]
            ],        
    ],



   'principalmanageclass' => [
            'update' => [
                'query' => "UPDATE `enrollments`
                            SET `en_teID` = ?
                            WHERE `en_shoolyear` = ? 
                            AND `en_section` = ?
                            AND `en_grade` = ?;
                            ", 
               'bind'  => "isss",
               'value' => []
            ], 
            

            'selectteacher' => [
                'query' => "SELECT DISTINCT 
                                ust.us_id,
                                CONCAT(ust.us_lname, ', ', ust.us_fname, ', ', ust.us_mname) AS name,
                                ust.us_email
                            FROM 
                                `users` ust
                            LEFT JOIN 
                                `enrollments` en ON en.en_teID = ust.us_id
                            WHERE 
                                ust.us_type = 'Teacher'  
                                AND ust.us_status = 'Active'
                                AND (en.en_teID IS NULL OR NOT EXISTS (
                                    SELECT 1
                                    FROM `enrollments` en1
                                    WHERE en1.en_teID = en.en_teID AND en1.en_shoolyear = ?
                                ));;
                            ", 
               'bind'  => "s",
               'value' => [$school_year]
            ], 
    ],
    


    'principalcalendar' => [
            'approve' => [
               'query' => "SELECT `ev_id`,
                            `ev_title`, 
                            `ev_description`,
                            `ev_type`,
                             CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                             CASE 
                                WHEN ev.ev_sdate = ev.ev_edate THEN DATE_FORMAT(ev.ev_sdate, '%b %d, %Y')  
                                ELSE CONCAT(DATE_FORMAT(ev.ev_sdate, '%b %d, %Y'), ' - ', DATE_FORMAT(ev.ev_edate, '%b %d, %Y'))
                            END AS `date`,
                            CASE 
                                WHEN ev.ev_stime = '00:00:00' || ev.ev_etime = '00:00:00' THEN 'NA'
                                ELSE CONCAT(
                                    DATE_FORMAT(ev.ev_stime, '%h:%i %p'), 
                                    ' - ', 
                                    DATE_FORMAT(ev.ev_etime, '%h:%i %p')
                                )
                            END AS `time`,
                            DATE_FORMAT(ev.fi_timestamp, '%b %d, %Y') AS `requested`
                        FROM `events` ev
                        INNER JOIN users us ON ev_usID = us.us_id AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian') 
                        WHERE ev_status = ? AND ev_assign = ?
                        ORDER BY ev.fi_timestamp ASC, ev.ev_stime ASC", 
               'bind'  => "ss",
               'value' => ['Accepted', 'Event']
            ], 
            

            'upcoming' => [
               'query' => "SELECT 
                                `ev_id`,
                                `ev_title`, 
                                `ev_description`,
                                `ev_type`,
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                CASE 
                                    WHEN ev.ev_sdate = ev.ev_edate THEN DATE_FORMAT(ev.ev_sdate, '%b %d, %Y')  
                                    ELSE CONCAT(DATE_FORMAT(ev.ev_sdate, '%b %d, %Y'), ' - ', DATE_FORMAT(ev.ev_edate, '%b %d, %Y'))
                                END AS `date`,
                                ev.ev_sdate, ev.ev_edate,
                                CASE 
                                WHEN ev.ev_stime = '00:00:00' || ev.ev_etime = '00:00:00' THEN 'NA'
                                ELSE CONCAT(
                                    DATE_FORMAT(ev.ev_stime, '%h:%i %p'), 
                                    ' - ', 
                                    DATE_FORMAT(ev.ev_etime, '%h:%i %p')
                                )
                                END AS `time`,
                                ev.ev_stime, ev.ev_etime,
                                DATE_FORMAT(ev.fi_timestamp, '%b %d, %Y') AS `requested`
                            FROM 
                                `events` ev
                            INNER JOIN 
                                users us 
                                ON ev.ev_usID = us.us_id 
                                AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian')
                            WHERE 
                                ev_status = ? 
                                AND ev_sdate > NOW()
                                ORDER BY ev.ev_sdate ASC, ev.ev_stime DESC LIMIT 5", 
                            
               'bind'  => "s",
               'value' => ['Accepted']
            ], 


            'rejected' => [
               'query' => "SELECT 
                                `ev_id`,
                                `ev_title`, 
                                `ev_description`,
                                `ev_type`,
                                `ev_remarks`,
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                CASE 
                                    WHEN ev.ev_sdate = ev.ev_edate THEN DATE_FORMAT(ev.ev_sdate, '%b %d, %Y')  
                                    ELSE CONCAT(DATE_FORMAT(ev.ev_sdate, '%b %d, %Y'), ' - ', DATE_FORMAT(ev.ev_edate, '%b %d, %Y'))
                                END AS `date`,
                                ev.ev_sdate, ev.ev_edate,
                                CASE 
                                WHEN ev.ev_stime = '00:00:00' || ev.ev_etime = '00:00:00' THEN 'NA'
                                ELSE CONCAT(
                                    DATE_FORMAT(ev.ev_stime, '%h:%i %p'), 
                                    ' - ', 
                                    DATE_FORMAT(ev.ev_etime, '%h:%i %p')
                                )
                                END AS `time`,
                                ev.ev_stime, ev.ev_etime,
                                DATE_FORMAT(ev.fi_timestamp, '%b %d, %Y') AS `requested`
                            FROM 
                                `events` ev
                            INNER JOIN 
                                users us 
                                ON ev.ev_usID = us.us_id 
                                AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian')
                            WHERE 
                                ev_status = ?
                                ORDER BY ev.fi_timestamp DESC, ev.ev_stime DESC LIMIT 5", 
                            
               'bind'  => "s",
               'value' => ['Rejected']
            ], 


            'pending' => [
               'query' => "SELECT 
                                ev.ev_id,
                                ev.ev_title, 
                                ev.ev_description,
                                ev.ev_type,
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                CASE 
                                    WHEN ev.ev_sdate = ev.ev_edate THEN DATE_FORMAT(ev.ev_sdate, '%b %d, %Y')  
                                    ELSE CONCAT(DATE_FORMAT(ev.ev_sdate, '%b %d, %Y'), ' - ', DATE_FORMAT(ev.ev_edate, '%b %d, %Y'))
                                END AS `date`,
                                CASE 
                                WHEN ev.ev_stime = '00:00:00' || ev.ev_etime = '00:00:00' THEN 'NA'
                                ELSE CONCAT(
                                    DATE_FORMAT(ev.ev_stime, '%h:%i %p'), 
                                    ' - ', 
                                    DATE_FORMAT(ev.ev_etime, '%h:%i %p')
                                )
                                END AS `time`,
                                DATE_FORMAT(ev.fi_timestamp, '%b %d, %Y') AS `requested`
                            FROM 
                                events ev
                            INNER JOIN 
                                users us ON ev.ev_usID = us.us_id AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian') 
                            WHERE 
                                ev.ev_status = ?
                            ORDER BY 
                               ev.fi_timestamp ASC, ev.ev_stime ASC", 
               'bind'  => "s",
               'value' => ['Pending']
            ], 


            'addevent' => [
               'query' => "INSERT INTO `events`(`ev_usID`, `ev_assign`, `ev_title`, `ev_description`, `ev_type`, `ev_sdate`, `ev_edate`, `ev_stime`, `ev_etime`, `ev_status`) 
                           VALUES (?, ? , ?, ? ,? , ?, ? , ?, ?, ?)", 
               'bind'  => "isssssssss",
               'value' => []
            ], 


            'editevent' => [
               'query' => "UPDATE `events` 
                            SET `ev_title`= ?,
                                `ev_description`= ?,
                                `ev_sdate`= ?,
                                `ev_edate`= ?,
                                `ev_stime`= ?,
                                `ev_etime`= ? 
                            WHERE ev_id = ?",
              'bind'  => "ssssssi",
               'value' => []
            ], 


            'statusevent' => [
               'query' => "UPDATE `events` 
                           SET ev_status = ?
                           WHERE ev_id = ?",
              'bind'  => "si",
               'value' => []
            ],
            

            'statuseventreject' => [
               'query' => "UPDATE `events` 
                           SET ev_status = ?, ev_remarks = ?
                           WHERE ev_id = ?",
              'bind'  => "ssi",
               'value' => []
            ], 

             
            'submissiondrop' => [
               'query' => "SELECT ev.`ev_id`, ev.`ev_title`, ev.`ev_sdate`, ev.`ev_edate`,
                            CASE 
                                WHEN NOT EXISTS (
                                    SELECT 1 
                                    FROM `events` 
                                    WHERE `ev_title` = '1st Quarter' 
                                    AND `ev_assign` = 'SY' 
                                    AND `ev_status` = 'Accepted'
                                    AND `ev_sdate` >= ev.`ev_sdate`
                                    AND `ev_edate` <= ev.`ev_edate`
                                )
                                THEN '1st Quarter'
                            END AS `1st`,
                            CASE 
                                WHEN NOT EXISTS (
                                    SELECT 1 
                                    FROM `events` 
                                    WHERE `ev_title` = '2nd Quarter' 
                                    AND `ev_assign` = 'SY' 
                                    AND `ev_status` = 'Accepted'
                                    AND `ev_sdate` >= ev.`ev_sdate`
                                    AND `ev_edate` <= ev.`ev_edate`
                                )
                                THEN '2nd Quarter'
                            END AS `2nd`,
                            CASE 
                                WHEN NOT EXISTS (
                                    SELECT 1 
                                    FROM `events` 
                                    WHERE `ev_title` = '3rd Quarter' 
                                    AND `ev_assign` = 'SY' 
                                    AND `ev_status` = 'Accepted'
                                    AND `ev_sdate` >= ev.`ev_sdate`
                                    AND `ev_edate` <= ev.`ev_edate`
                                )
                                THEN '3rd Quarter'
                            END AS `3rd`,
                            CASE 
                                WHEN NOT EXISTS (
                                    SELECT 1 
                                    FROM `events` 
                                    WHERE `ev_title` = '4th Quarter' 
                                    AND `ev_assign` = 'SY' 
                                    AND `ev_status` = 'Accepted'
                                    AND `ev_sdate` >= ev.`ev_sdate`
                                    AND `ev_edate` <= ev.`ev_edate`
                                )
                                THEN '4th Quarter'
                            END AS `4th`
                            FROM `events` AS ev
                            WHERE ev.`ev_assign` = 'SY' 
                            AND ev.`ev_status` = ?
                            AND ev.`ev_title` = (
                                SELECT MAX(`ev_title`)
                                FROM `events`
                                WHERE `ev_assign` = 'SY' 
                                AND `ev_status` = 'Accepted'
                                AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                            )",
              'bind'  => "s",
               'value' => ['Accepted']
            ], 


             'schoolyear' => [
               'query' => "SELECT 
                                `ev_id`, 
                                COALESCE(SUBSTRING_INDEX(MAX(`ev_title`), '-', -1), YEAR(CURDATE())) AS `year`,
                                DATE_ADD(`ev_edate`, INTERVAL 1 DAY) AS `ev_edate`
                            FROM `events`
                            WHERE `ev_assign` = 'SY' 
                                AND `ev_status` = 'accepted'
                                AND (`ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$' OR NOT EXISTS (
                                        SELECT 1 FROM `events`
                                        WHERE `ev_assign` = 'SY' 
                                            AND `ev_status` = ?
                                            AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                                ));",
              'bind'  => "s",
               'value' => ['Accepted']
            ],
    ],



    'principalviewteachers' => [
            'display' => [
                'query' => "SELECT 
                                 us.us_id, us.us_email,
                                 CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                (SELECT MAX(en_shoolyear) FROM `enrollments` LIMIT 1) AS schoolyear
                            FROM 
                                `users` us
                            WHERE 
                                us.us_type = 'Teacher' 
                                AND us.us_status = 'Active'
                                AND ?", 
               'bind'  => "i",
               'value' => [1]
            ],  
    ],



    'principalteacherprofile' => [
            'display' => [
                'query' => "SELECT us_id,
                            CONCAT(us_lname, ', ', us_fname, ', ', us_mname) AS name, 
                            `us_contact`,
                            `us_email`
                            FROM `users` 
                            WHERE us_id = ?;", 
               'bind'  => "i",
               'value' => [$teacher]
            ], 
            

            'display1' => [
                'query' => "SELECT 
                                CONCAT(us_lname, ', ', us_fname, ', ', us_mname) AS name, 
                                `us_birthday`, 
                                `us_gender`, 
                                 CONCAT(
                                    REGEXP_REPLACE(us_province, '[^a-zA-Z]', ''), ', ',
                                    REGEXP_REPLACE(us_municipality, '[^a-zA-Z]', ''), ', ',
                                    REGEXP_REPLACE(us_barangay, '[^a-zA-Z]', ''), ', ',
                                    us_street
                                ) AS address
                            FROM 
                                `users`
                            WHERE 
                                us_id = ?", 
               'bind'  => "i",
               'value' => [$teacher ]
            ], 


            'addvisoryclass' => [
                'query' => "SELECT 
                                en.en_section, 
                                en.en_shoolyear, 
                                en.en_grade,
                                COUNT(1) AS number
                            FROM 
                                `enrollments` en
                            WHERE 
                                en.en_shoolyear = (
                                    SELECT MAX(`ev_title`) 
                                    FROM `events`
                                    WHERE 
                                        `ev_assign` = 'SY' 
                                        AND `ev_status` = 'Accepted'
                                        AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                                        AND (LEFT(`ev_title`, 4) = YEAR(CURDATE()) 
                                            OR RIGHT(`ev_title`, 4) = YEAR(CURDATE()) + 1)
                                )
                            AND 
                                en.en_teID = ? 
                            GROUP BY  
                                en.en_section, en.en_shoolyear, en.en_grade;", 
               'bind'  => "i",
               'value' => [$teacher ]
            ], 
    ],



    'principalstuddirectory' => [
            'display' => [
                'query' => "SELECT
                                us.us_id, 
                                st.st_lrn,
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                us.us_gender,
                                TIMESTAMPDIFF(YEAR, STR_TO_DATE(us.us_birthday, '%c/%e/%y'), CURDATE()) - 
                                (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(STR_TO_DATE(us.us_birthday, '%c/%e/%y'), '%m%d')) AS age,
                                MAX(en.en_status) as en_status,
                                en.en_shoolyear,
                                CASE WHEN en.en_remarks IS NULL THEN '-' ELSE en.en_remarks END AS en_remarks
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            WHERE 
                                ?
                            GROUP BY CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname), us.us_id
                            ORDER BY 
                                us.us_gender DESC, 
                                us.us_lname ASC", 
                'bind'  => "i",
                'value' => [1]
            ],


            'status' => [
                'query' => "SELECT DISTINCT en.en_status 
                            FROM `enrollments` en
                            WHERE ?
                            ", 
                'bind'  => "i",
                'value' => [1]
            ],
    ],


    'principalstudentinfo' => [
            'display' => [
                'query' => "SELECT 
                                us.us_id, 
                                st.st_lrn, 
                                CONCAT(us.us_lname,', ',us.us_fname,', ',us.us_mname) AS 'name',
                                us.us_birthday AS birth,
                                DATE_FORMAT(NOW(), '%d %b %Y') AS 'current_date',
                                TIMESTAMPDIFF(YEAR, STR_TO_DATE(us.us_birthday, '%c/%e/%y'), CURDATE()) - 
                                (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(STR_TO_DATE(us.us_birthday, '%c/%e/%y'), '%m%d')) AS age,
                                us.us_gender,
                                CONCAT(us.us_province, ', ', us.us_municipality, ', ', us.us_barangay, ', ', us.us_street) AS address,
                                st.st_mothertongue,
                                st.st_religion,
                                st.st_ip,
                                (SELECT co.us_contact
                                FROM users co 
                                WHERE co.us_id = st.st_faID LIMIT 1) AS `st_contact`,
                                (SELECT CONCAT(mo.us_fname, ' ', mo.us_mname, ' ', mo.us_lname) 
                                FROM users mo 
                                WHERE mo.us_id = st.st_faID LIMIT 1) AS father,
                                (SELECT CONCAT(mo.us_fname, ' ', mo.us_mname, ' ', mo.us_lname) 
                                FROM users mo 
                                WHERE mo.us_id = st.st_moID LIMIT 1) AS mother,
                                (SELECT CONCAT(mo.us_fname, ' ', mo.us_mname, ' ', mo.us_lname) 
                                FROM users mo 
                                WHERE mo.us_id = st.st_guID LIMIT 1) AS guardian
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            WHERE 
                                us.us_id = ?
                            GROUP BY us.us_id", 
                'bind'  => "i",
                'value' => [$student]
            ],


            'classdisplay' => [
                'query' => "SELECT en.en_id,  en.en_shoolyear, en.en_grade, en.en_section 
                            FROM `enrollments` en
                            INNER JOIN `users` us ON en.en_stID = us.us_id
                            WHERE 
                            us.us_id = ?
                            ORDER BY en.en_shoolyear DESC", 
                'bind'  => "i",
                'value' => [$student]
            ],

    ],



    'principalviewstudentsgrades' => [
            'display' => [
                'query' => "SELECT 
                                genaverage.us_id,
                                genaverage.st_lrn,
                                genaverage.name, 
                                genaverage.us_gender,
                                genaverage.age, 
                                CASE 
                                    WHEN EXISTS (
                                        SELECT 1 
                                        FROM `student grades` 
                                        WHERE 
                                            (sg_grade = 0 AND sg_enID = genaverage.en_id)
                                            OR NOT EXISTS (SELECT 1 FROM `student grades` sg1 WHERE sg1.sg_enID = genaverage.en_id)
                                    ) THEN 'NA'
                                    ELSE CASE 
                                            WHEN AVG(genaverage.subject_avg) BETWEEN 90 AND 94 THEN 'With Honor'
                                            WHEN AVG(genaverage.subject_avg) BETWEEN 95 AND 97 THEN 'With High Honors'
                                            WHEN AVG(genaverage.subject_avg) BETWEEN 98 AND 100 THEN 'With Highest Honors'
                                            ELSE 'No Honor'
                                        END
                                END AS rank
                            FROM (
                                SELECT 
                                    CASE 
                                        WHEN sf.sf_name IN ('Music', 'PE', 'Arts', 'Health') THEN 'MAPEH'
                                        ELSE sf.sf_name 
                                    END AS subject_category,
                                    en.en_id, 
                                    us.us_id, 
                                    st.st_lrn,
                                    CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                    us.us_gender,
                                    TIMESTAMPDIFF(YEAR, STR_TO_DATE(us.us_birthday, '%c/%e/%y'), CURDATE()) - 
                                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(STR_TO_DATE(us.us_birthday, '%c/%e/%y'), '%m%d')) AS age,
                                    ROUND(AVG(NULLIF(sg.sg_grade, 0)), 2) AS subject_avg
                                FROM 
                                    `users` us
                                INNER JOIN 
                                    `student information` st ON us.us_id = st.st_stID
                                INNER JOIN 
                                    `enrollments` en ON us.us_id = en.en_stID
                                LEFT JOIN 
                                    `student grades` sg ON en.en_id = sg.sg_enID
                                LEFT JOIN 
                                    `sf 1 / 10` sf ON sg.sg_sfID = sf.sf_id
                                WHERE 
                                    en.en_id = ?
                                GROUP BY 
                                    subject_category, 
                                    en.en_id
                                ORDER BY 
                                    us.us_gender DESC, us.us_lname ASC
                            ) AS genaverage
                            GROUP BY 
                                genaverage.en_id;", 
                'bind'  => "i",
                'value' => [$enroll]
            ],


            'perquarters' => [
                'query' => "SELECT 
                                sf.sf_name AS subject_name, 
                                CASE 
                                    WHEN sg.sg_grade != 0 THEN sg.sg_grade 
                                    ELSE '-'
                                END AS grade
                               
                            FROM 
                                `enrollments` en
                            INNER JOIN 
                                `student grades` sg ON en.en_id = sg.sg_enID
                            INNER JOIN 
                                `sf 1 / 10` sf ON sg.sg_sfID = sf.sf_id
                            WHERE 
                                en.en_id = ?
                                AND sg.sg_quarter = ?
                                ORDER BY 
                                sf.sf_name ASC", 
                
                'bind'  => "ii",
                'value' => [$enroll, 1]
            ],


            'allquarters' => [
                'query' => "SELECT 
                                sf.sf_name AS Subject,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 1 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `1st Quarter`,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 2 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `2nd Quarter`,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 3 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `3rd Quarter`,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 4 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `4th Quarter`,
                                CASE 
                                    WHEN (SELECT 1 
                                        FROM `student grades` 
                                        WHERE sg_grade = 0 AND sg_enID = en.en_id LIMIT 1) > 0 THEN 'NA'
                                    ELSE ROUND(AVG(NULLIF(sg.sg_grade, 0)), 2) 
                                END AS `average`
                            FROM 
                                `enrollments` en
                            INNER JOIN 
                                `student grades` sg ON en.en_id = sg.sg_enID
                            INNER JOIN 
                                `sf 1 / 10` sf ON sg.sg_sfID = sf.sf_id
                            WHERE 
                                en.en_id = ?  
                            GROUP BY 
                                sf.sf_name
                            ORDER BY 
                                sf.sf_name ASC",
                
                'bind'  => "i",
                'value' => [$enroll]
            ],
    ],



    'principalclassarchives' => [
            'display' => [
                'query' => "SELECT en_shoolyear, en_grade, en_section 
                            FROM `enrollments` 
                            WHERE 
                            ?
                            GROUP BY en_shoolyear, en_grade, en_section
                            ORDER BY en_shoolyear DESC, en_grade ASC", 
                'bind'  => "i",
                'value' => [1]
            ],


            'schoolyear' => [
                'query' => "SELECT  DISTINCT en_shoolyear 
                            FROM `enrollments` 
                            WHERE ?
                            ORDER BY en_shoolyear DESC", 
                'bind'  => "i",
                'value' => [1]
            ],


            'gradelevel' => [
                'query' => "SELECT  DISTINCT en_grade 
                            FROM `enrollments`
                            WHERE ? 
                            ORDER BY en_grade ASC", 
                'bind'  => "i",
                'value' => [1]
            ],
    ],



    'principalviewstudlist' => [
            'display' => [
                'query' => "SELECT 
                                genaverage.us_id,
                                genaverage.st_lrn,
                                genaverage.en_shoolyear,
                                genaverage.name, 
                                genaverage.age,
                                genaverage.teacher,
                                genaverage.us_gender, 
                                CASE 
                                    WHEN EXISTS (
                                        SELECT 1 
                                        FROM `student grades` 
                                        WHERE 
                                        (sg_grade = 0  AND sg_enID = genaverage.en_id)
                                        OR
                                        NOT EXISTS (SELECT 1 FROM `student grades` sg1 WHERE sg1.sg_enID = genaverage.en_id)
                                    ) THEN 'NA'
                                    ELSE AVG(genaverage.subject_avg)
                                END AS general_average
                            FROM (
                                SELECT 
                                    CASE 
                                        WHEN sf.sf_name IN ('Music', 'PE', 'Arts', 'Health') THEN 'MAPEH'
                                        ELSE sf.sf_name 
                                    END AS subject_category,
                                    en.en_shoolyear,
                                    en.en_id, 
                                    us.us_id, 
                                    st.st_lrn,
                                    CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                    CONCAT(ust.us_lname, ', ', ust.us_fname, ', ', ust.us_mname) AS teacher,
                                    us.us_gender,
                                    TIMESTAMPDIFF(YEAR, STR_TO_DATE(us.us_birthday, '%c/%e/%y'), CURDATE()) - 
                                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(STR_TO_DATE(us.us_birthday, '%c/%e/%y'), '%m%d')) AS age,
                                    ROUND(AVG(NULLIF(sg.sg_grade, 0)), 2) AS subject_avg
                                FROM 
                                    `users` us
                                INNER JOIN 
                                    `student information` st ON us.us_id = st.st_stID
                                INNER JOIN 
                                    `enrollments` en ON us.us_id = en.en_stID
                                LEFT JOIN 
                                     `users` ust ON en.en_teID = ust.us_id
                                LEFT JOIN 
                                    `student grades` sg ON en.en_id = sg.sg_enID
                                LEFT JOIN 
                                    `sf 1 / 10` sf ON sg.sg_sfID = sf.sf_id
                                WHERE 
                                    (en.en_status = 'Enrolled' OR en.en_status = 'Pending') 
                                    AND en.en_shoolyear = ? 
                                    AND en.en_section = ?
                                    AND en.en_grade = ? 
                                GROUP BY 
                                    subject_category, 
                                    en.en_id, 
                                    st.st_lrn
                                ORDER BY 
                                    us.us_gender DESC, us.us_lname ASC
                            ) AS genaverage
                            GROUP BY 
                                genaverage.en_id", 
                'bind'  => "sss",
               'value' => [$school_year,$section, $grade]
            ],        
    ],



    'principaltracker' => [
            'display' => [
                'query' => "SELECT 
                                us.us_id, 
                                st.st_lrn,
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                us.us_gender,
                                fo.fo_name,
                                doc.do_remarks,
                                doc.do_timestamp
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                `documents` doc ON doc.do_stID = us.us_id
                            INNER JOIN 
                                `format` fo ON fo.fo_id = doc.do_foID AND fo.fo_type = 'Template'
                            WHERE ?
                            ORDER BY 
                                doc.do_timestamp DESC,
                                us.us_gender DESC, 
                                us.us_lname ASC
                                ", 
                'bind'  => "i",
                'value' => [1]
            ],        
    ],



    'principalgenerate' => [
            'display' => [
                'query' => "SELECT
                                us.us_id, 
                                st.st_lrn,
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                us.us_gender,
                                TIMESTAMPDIFF(YEAR, STR_TO_DATE(us.us_birthday, '%c/%e/%y'), CURDATE()) - 
                                (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(STR_TO_DATE(us.us_birthday, '%c/%e/%y'), '%m%d')) AS age
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            WHERE
                                ?
                            ORDER BY 
                                us.us_gender DESC, us.us_lname ASC;
                            ", 
                'bind'  => "i",
                'value' => [1]
            ], 

            
            'documents' => [
                'query' => "SELECT `fo_id`, `fo_name` 
                            FROM 
                                 `format` WHERE fo_type = ?", 
                'bind'  => "s",
                'value' => ['Template']
            ],


            'checkstudent' => [
                'query' => "SELECT st.st_lrn, fo.fo_name,  DATE_FORMAT(doc.do_timestamp, '%b %d, %Y') AS date
                            FROM 
                                  `users` us
                            INNER JOIN 
                                   `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                   `documents` doc ON doc.do_stID = us.us_id
                            INNER JOIN 
                                   `format` fo ON fo.fo_id = doc.do_foID
                            WHERE 
                                    us.us_id = ?
                            AND     fo.fo_id = ?
                            AND     fo.fo_type = 'Template'
                            ", 
                'bind'  => "ii",
                'value' => []
            ],

            
            'checkstudentgrade' => [
                'query' => "SELECT 
                                1
                            FROM 
                                `enrollments` en 
                            LEFT JOIN 
                                `student grades` sg 
                                ON en.en_id = sg.sg_enID
                            WHERE 
                                en_stID = ?
                                AND (sg.sg_grade = 0 OR sg.sg_enID IS NULL)
                            GROUP BY  
                                en.en_section, 
                                en.en_shoolyear, 
                                en.en_grade, 
                                sg.sg_quarter", 
                'bind'  => "i",
                'value' => []
            ],

            
            'checkstudentrequirements' => [
                'query' => "SELECT 
                                CASE 
                                    WHEN COUNT(fr.fo_id) = (SELECT COUNT(*) FROM `format` WHERE fo_type = 'Requirement'  AND fo.fo_id != 14) 
                                    THEN 1 
                                    ELSE 0 
                                END AS `all`
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                `documents` doc ON doc.do_stID = us.us_id
                            INNER JOIN 
                                `format` fo ON fo.fo_id = doc.do_foID
                            INNER JOIN 
                                `format` fr ON fr.fo_id = fo.fo_id
                            WHERE 
                                us.us_id = ? 
                                AND fr.fo_type = 'Requirement'
                                AND fo.fo_id != 14
                               ", 
                'bind'  => "i",
                'value' => []
            ],


           'checkstudentSF10' => [
                'query' => "WITH MinGrade AS (
                                SELECT MIN(e.en_grade) AS min_grade
                                FROM `enrollments` e 
                                INNER JOIN users ust ON e.en_teID = ust.us_id AND ust.us_type = 'Teacher'
                                WHERE e.en_stID = ?
                            )

                            SELECT 
                                en.en_stID, 
                                en.en_id, 
                                sg.sg_enID,
                                en.en_grade,
                                IFNULL(
                                    NULLIF(
                                        CONCAT_WS(', ',
                                            CASE WHEN (SELECT min_grade FROM MinGrade) > 6 AND (
                                                EXISTS (SELECT 1 FROM `student grades` sgg WHERE sgg.sg_enID = en.en_id AND sgg.sg_grade = 0 AND en.en_grade = 6) 
                                                OR (NOT EXISTS (SELECT 1 FROM enrollments enn WHERE enn.en_stID = en.en_stID AND enn.en_grade = 6) OR (sg.sg_enID IS NULL AND en_grade = 6)))
                                                THEN '6' 
                                            END,
                                            CASE WHEN (SELECT min_grade FROM MinGrade) > 5 AND (
                                                EXISTS (SELECT 1 FROM `student grades` sgg WHERE sgg.sg_enID = en.en_id AND sgg.sg_grade = 0 AND en.en_grade = 5) 
                                                OR (NOT EXISTS (SELECT 1 FROM enrollments enn WHERE enn.en_stID = en.en_stID AND enn.en_grade = 5) OR (sg.sg_enID IS NULL AND en_grade = 5)))
                                                THEN '5' 
                                            END,
                                            CASE WHEN (SELECT min_grade FROM MinGrade) > 4 AND (
                                                EXISTS (SELECT 1 FROM `student grades` sgg WHERE sgg.sg_enID = en.en_id AND sgg.sg_grade = 0 AND en.en_grade = 4) 
                                                OR (NOT EXISTS (SELECT 1 FROM enrollments enn WHERE enn.en_stID = en.en_stID AND enn.en_grade = 4) OR (sg.sg_enID IS NULL AND en_grade = 4)))
                                                THEN '4' 
                                            END,
                                            CASE WHEN (SELECT min_grade FROM MinGrade) > 3 AND (
                                                EXISTS (SELECT 1 FROM `student grades` sgg WHERE sgg.sg_enID = en.en_id AND sgg.sg_grade = 0 AND en.en_grade = 3) 
                                                OR (NOT EXISTS (SELECT 1 FROM enrollments enn WHERE enn.en_stID = en.en_stID AND enn.en_grade = 3) OR (sg.sg_enID IS NULL AND en_grade = 3)))
                                                THEN '3' 
                                            END,
                                            CASE WHEN (SELECT min_grade FROM MinGrade) > 2 AND (
                                                EXISTS (SELECT 1 FROM `student grades` sgg WHERE sgg.sg_enID = en.en_id AND sgg.sg_grade = 0 AND en.en_grade = 2) 
                                                OR (NOT EXISTS (SELECT 1 FROM enrollments enn WHERE enn.en_stID = en.en_stID AND enn.en_grade = 2) OR (sg.sg_enID IS NULL AND en_grade = 2)))
                                                THEN '2' 
                                            END,
                                            CASE WHEN (SELECT min_grade FROM MinGrade) > 1 AND (
                                                EXISTS (SELECT 1 FROM `student grades` sgg WHERE sgg.sg_enID = en.en_id AND sgg.sg_grade = 0 AND en.en_grade = 1) 
                                                OR (NOT EXISTS (SELECT 1 FROM enrollments enn WHERE enn.en_stID = en.en_stID AND enn.en_grade = 1) OR (sg.sg_enID IS NULL AND en_grade = 1)))
                                                THEN '1' 
                                            END
                                        ), ''
                                    ), 'TRO'
                                ) AS missing      
                            FROM 
                                enrollments en
                            LEFT JOIN 
                                `student grades` sg ON en.en_id = sg.sg_enID
                            WHERE 
                                en.en_stID = ?
                            GROUP BY 
                                en.en_id;", 
                'bind'  => "ii",
                'value' => []
            ],



            'checkstudentverify' => [
                'query' => "SELECT 1 FROM `enrollments` en
                            WHERE 
                            en.en_stID = ?
                            AND en.en_verify = 'Unverified'", 
                'bind'  => "i",
                'value' => []
            ],



             //Documents
            'documentss' => [
                'query' => "SELECT 
                                f.fo_text, 
                                user_info.en_id, 
                                user_info.st_lrn, 
                                user_info.sname,
                                user_info.tname,
                                user_info.en_grade,
                                user_info.en_section,
                                user_info.en_shoolyear,
                                principal_info.pname,
                                AVG(NULLIF(sg.sg_grade, 0)) AS avg_grade
                            FROM 
                                `format` f
                            INNER JOIN (
                                SELECT 
                                    en.en_id,
                                    st.st_lrn, 
                                    CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS sname,
                                    en.en_grade,
                                    en.en_section,
                                    en.en_shoolyear, 
                                    CONCAT(us1.us_lname, ', ', us1.us_fname, ', ', us1.us_mname) AS tname
                                FROM 
                                    `users` us
                                INNER JOIN 
                                    `student information` st ON us.us_id = st.st_stID
                                INNER JOIN
                                    `enrollments` en ON en.en_stID = us.us_id
                                INNER JOIN 
                                    `users` us1 ON us1.us_id = en.en_teID
                                WHERE 
                                    us.us_id = ? AND
                                    en.en_grade = (SELECT MAX(enn.en_grade) FROM enrollments enn WHERE enn.en_stID = us.us_id)
                            ) AS user_info ON 1=1 
                            INNER JOIN (
                                SELECT 
                                    CONCAT(us_principal.us_lname, ', ', us_principal.us_fname, ', ', us_principal.us_mname) AS pname
                                FROM 
                                    `users` us_principal
                                WHERE 
                                    us_principal.us_type = 'principal'
                                ORDER BY 
                                    us_principal.us_id DESC
                                LIMIT 1
                            ) AS principal_info ON 1=1
                            LEFT JOIN 
                                `student grades` sg ON sg.sg_enID = user_info.en_id
                            LEFT JOIN 
                                `sf 1 / 10` sf ON sg.sg_sfID = sf.sf_id
                            WHERE 
                                f.fo_id = ?", 
                'bind'  => "ii",
                'value' => []
            ],
            
            
            'templatedocuments' => [
                'query' => "UPDATE `format` SET `fo_text`= ? 
                            WHERE 
                            fo_id = ?", 
                'bind'  => "si",
                'value' => []
            ],


            'templatedocumentsinsert' => [
                'query' => "INSERT INTO `format`( `fo_type`, `fo_name`, `fo_text`) 
                            VALUES (?, ?, ?)", 
                'bind'  => "sss",
                'value' => []
            ],


            'templatedocumentsdelete' => [
                'query' => "DELETE FROM `format` 
                            WHERE fo_id = ?", 
                'bind'  => "i",
                'value' => []
            ],


            'templatedocuments1' => [
                'query' => "SELECT  `fo_id`, `fo_text` 
                            FROM `format`
                            WHERE ?", 
                'bind'  => "i",
                'value' => [1]
            ],


            'release' => [
                'query' => "INSERT INTO `documents`(`do_stID`, `do_riID`, `do_foID`, `do_remarks`) 
                            VALUES (?, ?, ?, ?)", 
                'bind'  => "iiis",
                'value' => []
            ],



            //SF10
             'sf_gradeLevel' => [
                'query' => "SELECT 
                                sf_name,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 1 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `1st Quarter`,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 2 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `2nd Quarter`,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 3 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `3rd Quarter`,
                                IFNULL(AVG(CASE WHEN sg.sg_quarter = 4 AND sg.sg_grade != 0 THEN sg.sg_grade END), '-') AS `4th Quarter`,
                                CASE 
                                    WHEN (SELECT 1 
                                        FROM `student grades` 
                                        WHERE sg_grade = 0 AND sg_enID = en.en_id AND sg_sfID = sf.sf_id LIMIT 1) > 0 THEN 'NA'
                                    ELSE ROUND(AVG(NULLIF(sg.sg_grade, 0)), 2) 
                                END AS `average`
                            FROM 
                                `enrollments` en
                            INNER JOIN 
                                `student grades` sg ON en.en_id = sg.sg_enID
                            INNER JOIN 
                                `sf 1 / 10` sf ON sg.sg_sfID = sf.sf_id
                            WHERE 
                                en.en_id = ?
                            GROUP BY 
                                sf.sf_name", 
                'bind'  => "i",
                'value' => []
            ],


            'sf_schoolyear' => [
                'query' => "SELECT 
                                en.en_id,
                                en.en_grade, 
                                en.en_section, 
                                en.en_shoolyear, 
                                CONCAT(ust.us_fname, '  ', ust.us_mname, '  ', ust.us_lname) AS name,
                                si.si_schoolID, 
                                si.si_name, 
                                si.si_region, 
                                si.si_division, 
                                si.si_district 
                            FROM 
                                `users` us
                            INNER JOIN 
                                enrollments en ON us.us_id = en.en_stID 
                            INNER JOIN 
                                `users` ust ON en.en_teID = ust.us_id
                            CROSS JOIN 
                                `school information` si
                            WHERE 
                                (en.en_status = 'Enrolled' OR en.en_status = 'Pending') AND
                                us.us_id = ? 
                            ORDER BY 
                                en.en_grade, 
                                en.en_shoolyear ASC;", 
                'bind'  => "i",
                'value' => []
            ],


            'sf_information' => [
                'query' => "SELECT
                                us.us_id, 
                                st.st_lrn,
                                us.us_lname,
                                us.us_fname, 
                                us.us_mname,
                                us.us_gender,
                                us.us_birthday
                            FROM 
                            `users` us
                            INNER JOIN 
                            `student information` st ON us.us_id = st.st_stID
                            WHERE us.us_id = ?", 
                'bind'  => "i",
                'value' => [75],
            ],

    ],

];

?>