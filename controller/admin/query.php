<?php 
require "function.php";
$admin = isset($_GET['admin']) ? $_GET['admin'] : 152;

$data = [
    'admin' => [
            'display' => [
               'query' => "SELECT 
                                CONCAT(UPPER(LEFT(us_type, 1)), LOWER(SUBSTRING(us_type, 2))) AS us_type,
                                COUNT(1) AS number                         
                            FROM 
                                `users` us 
                                WHERE 
                                us_type IN ('teacher', 'registrar', 'principal', 'secretary', 'admin')
                                AND us_status = ?
                            GROUP BY us_type
                            ORDER BY us_type ASC",           
               'bind'  => "s",
               'value' => ['Active']
            ], 

            'name' => [
               'query' => "SELECT CONCAT(us_lname, ', ', us_fname, ', ', us_mname) AS name
                           FROM `users` WHERE us_id = ?  AND us_type = 'admin'",           
               'bind'  => "i",
               'value' => [$admin]
            ], 
    ],


    'admincalendar' => [
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
                                `ev_usID`,
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
               'value' => ['Pending', $admin]
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



        'excelheader' => [
            'display' => [
               'query' => "SELECT 
                            `sf_id`, 
                            (CASE WHEN `sf_type` = 'info' THEN 'SF 1' ELSE 'SF 10' END) AS type, 
                            `sf_name`
                            FROM `sf 1 / 10` sf
                            WHERE sf.sf_status = ?
                            ORDER BY sf.sf_type ASC, sf.sf_name ASC", 
               'bind'  => "s",
               'value' => ['Active']
            ], 

            'insert' => [
               'query' => "INSERT INTO `sf 1 / 10`(`sf_type`, `sf_name`) 
                           VALUES (?,?)", 
               'bind'  => "ss",
               'value' => []
            ], 

            'update' => [
               'query' => "UPDATE `sf 1 / 10`
                           SET 
                            `sf_type` = CASE WHEN `sf_type` <> ? THEN ? ELSE `sf_type` END,
                            `sf_name` = CASE WHEN `sf_name` <> ? THEN ? ELSE `sf_name` END
                            WHERE `sf_id` = ?", 
               'bind'  => "ssssi",
               'value' => []
            ], 

            'delete' => [
               'query' => "UPDATE `sf 1 / 10`
                           SET `sf_status` = 'Inactive'
                           WHERE `sf_id` = ?;", 
               'bind'  => "i",
               'value' => []
            ], 



        ],


        'manageacc' => [
            'display' => [
               'query' => "SELECT `us_id`, 
                                `us_type`, 
                                CONCAT(us_lname, ', ', us_fname, ', ', us_mname) AS name,
                                `us_fname`, 
                                `us_mname`, 
                                `us_lname`,
                                `us_email`, 
                                `us_birthday`, 
                                `us_gender`, 
                                `us_contact`, 
                                `us_password`, 
                                `us_province`, 
                                `us_municipality`, 
                                `us_barangay`, 
                                `us_street` 
                            FROM `users` 
                            WHERE 
                            us_type IN ('teacher', 'registrar', 'principal', 'secretary', 'admin')
                            AND us_status = ?", 
               'bind'  => "s",
               'value' => ['Active']
            ], 

            'insert' => [
               'query' => "INSERT INTO `sf 1 / 10`(`sf_type`, `sf_name`) 
                           VALUES (?,?)", 
               'bind'  => "ss",
               'value' => []
            ], 

            'update' => [
               'query' => "UPDATE `sf 1 / 10`
                           SET 
                            `sf_type` = CASE WHEN `sf_type` <> ? THEN ? ELSE `sf_type` END,
                            `sf_name` = CASE WHEN `sf_name` <> ? THEN ? ELSE `sf_name` END
                            WHERE `sf_id` = ?", 
               'bind'  => "ssssi",
               'value' => []
            ], 

            'delete' => [
               'query' => "UPDATE `sf 1 / 10`
                           SET `sf_status` = 'Inactive'
                           WHERE `sf_id` = ?;", 
               'bind'  => "i",
               'value' => []
            ], 

            
           'register' => [
                'query' => "INSERT INTO `users`(`us_type`, `us_fname`, `us_mname`, `us_lname`, `us_birthday`, `us_gender`, `us_email`, `us_contact`,`us_password`,`us_province`, `us_municipality`,`us_barangay`, `us_street`) 
                            VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?)",
                'bind'  => "sssssssssssss",
                'value' => []
            ],

            'checkprincipal'=> [
                'query' => "SELECT 1 FROM `users` 
                            WHERE us_type = 'principal' 
                            AND us_status = ?",
                'bind'  => "s",
                'value' => ['Active']
            ],

            'checkemail'=> [
                'query' => "SELECT 1 FROM `users` WHERE us_email = ? AND us_status = 'Active'",
                'bind'  => "s",
                'value' => []
            ],

            'updatepersonal'=> [
                'query' => "UPDATE `users` 
                            SET `us_fname` = ?, 
                                `us_mname` = ?, 
                                `us_lname` = ?, 
                                `us_birthday` = ?, 
                                `us_gender` = ?, 
                                `us_contact` = ?, 
                                `us_province` = ?, 
                                `us_municipality` = ?, 
                                `us_barangay` = ?, 
                                `us_street` = ? 
                            WHERE `us_id` = ?
                            ",
                'bind'  => "ssssssssssi",
                'value' => []
            ],

            'updateaccount'=> [
                'query' => "UPDATE `users` 
                            SET `us_type`= ?,
                                `us_email`= ?,
                                `us_password`= ? 
                            WHERE us_id = ?",
                'bind'  => "sssi",
                'value' => []
            ],

            'accountemail'=> [
                'query' => "SELECT 1 
                            FROM `users` 
                            WHERE us_email = ? AND  us_id != ? AND us_status = 'active'",
                'bind'  => "si",
                'value' => []
            ],

             'accountdelete'=> [
                'query' => "UPDATE `users` SET `us_status`='inactive' WHERE us_id = ?",
                'bind'  => "i",
                'value' => []
            ],
        ],

        'schoolinfo' => [
            'display' => [
               'query' => "SELECT `si_id`, `si_schoolID`,  `si_name`, `si_region`, `si_division`, `si_district` FROM `school information` WHERE si_id = ?", 
               'bind'  => "i",
               'value' => [1]
            ], 

            'edit' => [
               'query' => "UPDATE `school information` SET `si_schoolID`= ?, `si_name`= ?,`si_region`= ?,`si_division`=  ?,`si_district`= ? WHERE si_id = 1", 
               'bind'  => "sssss",
               'value' => []
            ], 

        ]
];
?>