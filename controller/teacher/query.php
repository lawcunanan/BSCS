<?php 
require "function.php";
$grade = isset($_GET['grade']) ? $_GET['grade'] : 0;
$section = isset($_GET['section']) ? $_GET['section'] : 0;
$school_year = isset($_GET['school_year']) ? $_GET['school_year'] : 0;
$student = isset($_GET['student']) ? $_GET['student'] : 0;
$enroll = isset($_GET['enroll']) ? $_GET['enroll'] : 0;
$teacher = isset($_GET['teacher']) ? $_GET['teacher'] : 1;




$data = [
    'teacher' => [
            'display' => [
                'query' => "SELECT en_shoolyear, en_grade, en_section 
                            FROM `enrollments`  
                            WHERE en_teID = ? AND en_shoolyear =  (
                                    SELECT MAX(`ev_title`) 
                                    FROM `events`
                                    WHERE 
                                        `ev_assign` = 'SY' 
                                        AND `ev_status` = 'Accepted'
                                        AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                                        AND (LEFT(`ev_title`, 4) = YEAR(CURDATE()) 
                                            OR RIGHT(`ev_title`, 4) = YEAR(CURDATE()) + 1)
                                ) 
                                GROUP BY en_grade, en_section
                                ORDER BY en_grade ASC, en_section ASC", 
                'bind'  => "i",
               'value' => [$teacher]
            ], 

            'name' => [
               'query' => "SELECT CONCAT(us_lname, ', ', us_fname, ', ', us_mname) AS name
                           FROM `users` WHERE us_id = ?  AND us_type = 'teacher'",           
               'bind'  => "i",
               'value' => [$teacher]
            ], 

    ],



    'teacher_advisory' => [
            'display' => [
                'query' => "SELECT 
                                CASE WHEN sg.sg_quarter IS NOT NULL THEN sg.sg_quarter ELSE 'NA' END AS sg_quarter,
                                en.en_section, 
                                en.en_shoolyear, 
                                en.en_grade, 
                                DATE_FORMAT(MAX(CASE WHEN sg.sg_grade != ? THEN sg.sg_timestamp END), '%M %d, %Y') AS 'date'
                            FROM 
                                `enrollments` en
                            LEFT JOIN 
                                `student grades` sg ON en.en_id = sg.sg_enID
                            WHERE 
                                en_teID = ? AND
                                (en.en_status = 'Enrolled' ||  en.en_status = 'Pending') AND
                                (sg.sg_quarter = (
                                    SELECT MIN(sg2.sg_quarter) 
                                    FROM `enrollments` en2
                                    INNER JOIN `student grades` sg2 ON en2.en_id = sg2.sg_enID
                                    WHERE
                                        en2.en_section = en.en_section
                                    AND en2.en_shoolyear = en.en_shoolyear
                                    AND en2.en_grade = en.en_grade
                                    AND sg2.sg_grade = ? 
                                )
                                OR
                                    NOT EXISTS (SELECT 1 FROM `student grades` sg1 WHERE sg1.sg_enID = en.en_id)
                                )
                            GROUP BY 
                                en.en_section, 
                                en.en_shoolyear, 
                                en.en_grade
                            ORDER BY en_shoolyear DESC", 
                
                'bind'  => "iii",
                'value' => [0, $teacher, 0]
            ],


            'schoolyear' => [
                'query' => "SELECT  
                                    ev.`ev_title`,
                                    ev.`ev_id`,
                                    DATE_FORMAT(submission.`ev_sdate`, '%M %d, %Y') AS `submission_sdate`,
                                    submission.`ev_title` AS `quarter`
                                FROM `events` AS ev
                                LEFT JOIN (
                                    SELECT `ev_title`, `ev_sdate`, `ev_assign`
                                    FROM `events` 
                                    WHERE `ev_assign` = 'SY' 
                                    AND `ev_status` = 'Accepted'
                                    AND `ev_sdate` >= CURDATE() 
                                    ORDER BY `ev_sdate` ASC
                                    LIMIT 1
                                ) AS submission ON submission.`ev_assign` = ev.`ev_assign`
                                WHERE 
                                    ev.`ev_assign` = 'SY' 
                                    AND ev.`ev_status` = ?
                                    AND ev.`ev_title` = (
                                        SELECT MAX(`ev_title`)
                                        FROM `events`
                                        WHERE `ev_assign` = 'SY' 
                                        AND `ev_status` = 'Accepted'
                                        AND `ev_title` REGEXP '^[0-9]{4}-[0-9]{4}$'
                                        AND (LEFT(`ev_title`, 4) = YEAR(CURDATE()) 
                                            OR RIGHT(`ev_title`, 4) = YEAR(CURDATE()) + 1)
                                    );", 
                
                'bind'  => "s",
                'value' => ['Accepted']
            ],
        ],



    'teacher_submitgrades' => [
           'perquarter' => [
                'query' => "SELECT 
                                average.en_id,
                                average.st_lrn,
                                average.name,
                                average.us_gender,
                                average.sg_quarter,
                                CASE 
                                    WHEN average.quarterly IS NOT NULL  THEN ROUND(AVG(average.quarterly), 2) 
                                    ELSE '-' 
                                END AS quarterly
                            FROM (
                                SELECT 
                                    en.en_id, 
                                    st.st_lrn, 
                                    CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                    us.us_gender,
                                    CASE 
                                        WHEN sg_quarter IS NULL THEN '-' ELSE sg_quarter
                                     END AS sg_quarter,
                                    CASE 
                                        WHEN COUNT(NULLIF(sg.sg_grade, 0)) = 0 THEN null 
                                        ELSE ROUND(SUM(NULLIF(sg.sg_grade, 0)) / COUNT(NULLIF(sg.sg_grade, 0)), 2) 
                                    END AS quarterly,
                                    CASE 
                                        WHEN sf.sf_name IN ('Music', 'PE', 'Arts', 'Health') THEN 'MAPEH'
                                        ELSE sf.sf_name 
                                    END AS subject_category
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
                                    (en.en_status = 'Enrolled' OR en.en_status = 'Pending')
                                    AND en.en_shoolyear = ?
                                    AND en.en_section = ?
                                    AND en.en_grade = ? 
                                    AND (sg.sg_quarter = ? OR NOT EXISTS (SELECT 1 FROM `student grades` sg1 WHERE sg1.sg_enID = en.en_id))
                                GROUP BY
                                    subject_category,  
                                    en.en_id, 
                                    CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname)
                            ) as average
                            GROUP BY 
                                average.en_id, 
                                average.st_lrn, 
                                average.us_gender
                            ORDER BY 
                                average.us_gender DESC, 
                                average.name ASC;", 
                
                'bind'  => "ssss",
                'value' => [$school_year, $section, $grade, '1']
            ],


            'generalave' => [
                'query' => "SELECT 
                                    average.en_id,
                                    average.lrn,
                                    average.name,
                                    average.gender,
                                    (CASE WHEN ROUND(AVG(average.average), 2) IS NOT NULL THEN ROUND(AVG(average.average), 2) ELSE '-' END) AS average,
                                    GROUP_CONCAT(DISTINCT average.quarter_used ORDER BY average.quarter_used SEPARATOR ', ') AS quarterused
                                FROM (
                                   SELECT 
                                        en.en_id,
                                        st.st_lrn AS lrn,
                                        CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                        us.us_gender AS gender,
                                        CASE 
                                            WHEN sf.sf_name IN ('Music', 'PE', 'Arts', 'Health') THEN 'MAPEH'
                                            ELSE sf.sf_name 
                                        END AS subject_category,
                                        ROUND(AVG(NULLIF(sg.sg_grade, 0)), 2) AS average,
                                        CASE 
                                             WHEN sg.sg_quarter IS NULL THEN '-' ELSE GROUP_CONCAT(DISTINCT sg.sg_quarter ORDER BY sg.sg_quarter SEPARATOR ', ')
                                        END AS quarter_used
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
                                        (en.en_status = 'Enrolled' OR en.en_status = 'Pending')
                                        AND en.en_shoolyear = ? 
                                        AND en.en_section = ? 
                                        AND en.en_grade =  ? 
                                        AND (sg.sg_grade != 0 OR NOT EXISTS (SELECT 1 FROM `student grades` sg1 WHERE sg1.sg_enID = en.en_id))
                                GROUP BY 
                                     subject_category, 
                                    en.en_id, 
                                    CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) 
                                ORDER BY 
                                    us.us_gender DESC, us.us_lname ASC  
                            ) AS average
                            GROUP BY 
                                average.en_id, average.lrn, average.gender
                            ORDER BY 
                                    average.gender DESC, average.name ASC", 
                
                'bind'  => "sss",
                'value' => [$school_year, $section, $grade]
            ],


            'headersf10' => [
                'query' => "SELECT `sf_status`, 
                               `sf_name` AS subjects
                            FROM `sf 1 / 10`
                            WHERE `sf_type` = ? 
                            AND `sf_status` = 'Active'
                            ", 
                
                'bind'  => "s",
                'value' => ['subject']
            ],


    ], 
    


    'teacher_viewstudlist' => [
        
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
                'value' => [$school_year, $section, $grade]
            ],
        ],


        'teacher_studentinformation' => [
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
                            AND  en_teID = ?
                            ORDER BY en.en_shoolyear DESC", 
                
                'bind'  => "ii",
                'value' => [$student, $teacher]
            ],
        ],




        'teacher_viewstudentgrades' => [
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
                                    (en.en_status = 'Enrolled' OR en.en_status = 'Pending') 
                                    AND en.en_id = ?
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
                'value' => [ $enroll, 1]
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



        'teacher_handled' => [
            'handledclass' => [
                'query' => "SELECT en_shoolyear, en_grade, en_section 
                            FROM `enrollments` 
                            WHERE en_teID = ?
                            GROUP BY en_shoolyear, en_grade, en_section
                            ORDER BY en_shoolyear DESC, en_grade ASC", 
                
                'bind'  => "i",
                'value' => [$teacher]
            ],


            'schoolyear' => [
                'query' => "SELECT  DISTINCT en_shoolyear 
                            FROM `enrollments` 
                            WHERE en_teID = ?
                            ORDER BY en_shoolyear DESC", 
                
                'bind'  => "i",
                'value' => [$teacher]
            ],


            'gradelevel' => [
                'query' => "SELECT  DISTINCT en_grade 
                            FROM `enrollments` 
                            WHERE en_teID = ?
                            ORDER BY en_grade ASC", 
                
                'bind'  => "i",
                'value' => [$teacher]
            ],
        ],



        'teachercalendar' => [
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
                                `events` ev
                            INNER JOIN 
                                users us 
                                ON ev.ev_usID = us.us_id 
                                AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian')
                            WHERE 
                                ev_status = ?
                                AND ev_sdate > NOW()
                                ORDER BY ev.ev_sdate ASC, ev.ev_stime ASC LIMIT 5", 
                            
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
                                 ev.ev_sdate, ev.ev_edate,
                                 ev.ev_stime, ev.ev_etime,
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
                                `events` ev
                            INNER JOIN 
                                users us 
                                ON ev.ev_usID = us.us_id 
                                AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian')
                            WHERE 
                                ev_status = ? AND ev_usID = ?
                                ORDER BY ev.fi_timestamp DESC, ev.ev_stime DESC LIMIT 5", 
                            
               'bind'  => "si",
               'value' => ['Rejected', $teacher]
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
                                events ev
                            INNER JOIN 
                                users us ON ev.ev_usID = us.us_id AND us.us_status NOT IN ('Student', 'Father', 'Mother', 'Guardian') 
                            WHERE 
                                ev.ev_status = ? AND ev.ev_usID = ?
                            ORDER BY 
                               ev.fi_timestamp ASC, ev.ev_stime ASC", 
               'bind'  => "si",
               'value' => ['Pending', $teacher]
            ], 


            'addevent' => [
               'query' => "INSERT INTO `events`(`ev_usID`, `ev_title`, `ev_description`, `ev_type`, `ev_sdate`, `ev_edate`, `ev_stime`, `ev_etime`, `ev_status`) 
                           VALUES (?, ? , ?, ? ,? , ?, ? , ?, ?)", 
               'bind'  => "issssssss",
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
        ],
    ];


?>