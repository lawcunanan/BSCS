<?php 
function db_enrollStudents($students){
    $Output = [];
   //Check handled
    if(!isset($students['Handled']['grade level']) || !isset($students['Handled']['section']) || !isset($students['Handled']['school year'])){
        $Output['Handled']['type'] = 'handle_Enroll';
        return [false, $Output];
    }


    
    foreach ($students['Data'] as $id => $student) {
        $data = [
            'schoolyear' => [
                'query' => "SELECT 
                                1 AS 'check'
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            WHERE 
                                st.st_lrn = ? 
                                AND en.en_shoolyear = ?
                            ORDER BY 
                                en.en_shoolyear DESC 
                            LIMIT 1",
                'bind'  => "ss",
                'value' => []  
            ],
        ];

        $data['schoolyear']['value'] = [$student['lrn'], $students['Handled']['school year'] ]; 
        list($checkSuccess, $checkResult) = select($data['schoolyear']);

        if($checkSuccess && $checkResult[0]['check'] == 1){
            $Output['Handled']['type'] = 'schoolyear_Enroll';
            return [false, $Output];
        }

    }

    
  //enroll or update student info
   foreach ($students['Data'] as $id => $student) {
    $data = [
        'selectLrn' => [
            'query' => "SELECT `st_stID` FROM `student information` 
                        WHERE st_lrn = ?",
            'bind'  => "s",
            'value' => []  
        ],

        'insertStudent' => [
            'query' => "INSERT INTO `Users` (`us_type`, `us_lname`, `us_fname`, `us_mname`, `us_birthday`, `us_gender`,  `us_province`, `us_municipality`, `us_barangay`, `us_street`)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            'bind'  => "ssssssssss",
            'value' => []  
        ],

        'insertParent' => [
            'query' => "INSERT INTO `Users` (`us_type`, `us_lname`, `us_fname`, `us_mname`, `us_gender`, `us_contact`)
                               VALUES (?,?,?,?,?,?)",
            'bind'  => "ssssss",
            'value' => []  
        ],

        'updateStudentinfo' => [
            'query' => "UPDATE `student information` SET `st_faID`= ?,`st_moID`= ?,`st_guID`= ?,`st_lrn`= ?, `st_mothertongue`= ?,`st_ip`= ?,`st_religion`= ? 
                        WHERE st_stID = ?",
            'bind'  => "iiissssi",
            'value' => []  
        ],

        'enrollStudent' => [
            'query' => "INSERT INTO enrollments (en_stID, en_teID, en_grade, en_section, en_shoolyear) 
                        VALUES (?, ?, ?, ?, ?);",
            'bind'  => "iisss",
            'value' => []  
        ],

        'selectGrade' => [
            'query' => "SELECT 1 FROM `enrollments` WHERE en_grade = ? AND en_shoolyear = ? AND en_stID = ?",
            'bind'  => "ssi",
            'value' => []  
        ],

        'checkStudent' => [
                'query' => "SELECT
                                CONCAT(us.us_lname, ', ', us.us_fname, ', ', us.us_mname) AS name,
                                en.en_grade,
                                en.en_section,
                                en.en_shoolyear,
                                (
                                    SELECT
                                        1
                                    FROM 
                                        `enrollments` en2
                                    LEFT JOIN 
                                        `student grades` sg ON en2.en_id = sg.sg_enID
                                    WHERE
                                        (sg.sg_grade = 0 ||sg.sg_enID IS NULL)
                                        AND en2.en_id = en.en_id
                                LIMIT 1) AS 'check'
                            FROM 
                                `users` us
                            INNER JOIN 
                                `student information` st ON us.us_id = st.st_stID
                            INNER JOIN 
                                `enrollments` en ON us.us_id = en.en_stID
                            WHERE 
                                us.us_id = ? 
                                AND en.en_grade = (
                                    SELECT 
                                        MAX(en1.en_grade)
                                    FROM 
                                        `enrollments` en1 
                                    WHERE 
                                        en1.en_stID = en.en_stID AND (en1.en_status = 'Enrolled' ||   en1.en_status = 'Pending')
                                )
                            ORDER BY 
                                        en.en_shoolyear DESC 
                                   LIMIT 1",
                'bind'  => "i",
                'value' => []  
        ],

        'updateStundent' => [
            'query' => "UPDATE `student information` AS si
                        INNER JOIN `Users` AS u ON si.st_stID = u.us_id
                        SET 
                            -- Check and update fields from student information
                           
                            si.st_mothertongue = CASE WHEN si.st_mothertongue <> ? THEN ? ELSE si.st_mothertongue END,
                            si.st_ip = CASE WHEN si.st_ip <> ? THEN ? ELSE si.st_ip END,
                            si.st_religion = CASE WHEN si.st_religion <> ? THEN ? ELSE si.st_religion END,
                            
                            -- Compare and update fields from Users table
                            u.us_lname = CASE WHEN u.us_lname <> ? THEN ? ELSE u.us_lname END,
                            u.us_fname = CASE WHEN u.us_fname <> ? THEN ? ELSE u.us_fname END,
                            u.us_mname = CASE WHEN u.us_mname <> ? THEN ? ELSE u.us_mname END,
                            u.us_gender = CASE WHEN u.us_gender <> ? THEN ? ELSE u.us_gender END,
                            u.us_birthday = CASE WHEN u.us_birthday <> ? THEN ? ELSE u.us_birthday END,

                            u.us_province = CASE WHEN u.us_province <> ? THEN ? ELSE u.us_province END,
                            u.us_municipality = CASE WHEN u.us_municipality <> ? THEN ? ELSE u.us_municipality END,
                            u.us_barangay = CASE WHEN u.us_barangay <> ? THEN ? ELSE u.us_barangay END,
                            u.us_street = CASE WHEN u.us_street <> ? THEN ? ELSE u.us_street END

                        WHERE u.us_type = 'student' AND u.us_id = ?;
                        ",
            'bind'  => "ssssssssssssssssssssssssi",
            'value' => []  
        ],
       
        'updateParent' => [
            'query' => "",
            'bind' => "ssssssssi",
            'value' => []
        ],

        'updateStudentstatus' => [
            'query' => "UPDATE `enrollments` 
                        SET `en_status`= 'Pending' 
                        WHERE en_id = ?",
            'bind' => "i",
            'value' => []
        ],




    ];

     
        $data['selectLrn']['value'] = [$student['lrn']]; 
        list($checkSuccess, $checkResult) = select($data['selectLrn']);
        
        if($checkSuccess) {
               // update students info and enrolled";
               // echo "Student found!";
                $studentId = $checkResult[0]['st_stID'];
              
                $name = name($student['name']); 
              
                $data['updateStundent']['value'] = [
                                                   
                                                  $student['tongue'], $student['tongue'], 
                                                  $student['ip'], $student['ip'], 
                                                  $student['religion'], $student['religion'],
                                                  
                                                  
                                                  $name[0], $name[0],
                                                  $name[1], $name[1],
                                                  $name[2], $name[2],   
                                                  $student['sex'], $student['sex'],  
                                                  $student['birth'], $student['birth'], 
                                                  
                                                  $student['province'], $student['province'],
                                                  $student['municipal'], $student['municipal'],  
                                                  $student['barangay'], $student['barangay'],  
                                                  $student['purok'], $student['purok'],
                                                  $studentId 

                                               ];
                                              
                insert($data['updateStundent']);
                 
                $parents = [
                    ['name' => $student['father name'], 'id' => 'st_faID'],
                    ['name' => $student['mother name'], 'id' => 'st_moID'],
                    ['name' => $student['guardian name'], 'id' => 'st_guID'],
                ];

                foreach ($parents as $parent) {
                    $parentIdColumn = $parent['id'];
                    
                    // Use correct student ID dynamically
                    $data['updateParent']['query'] = "
                        UPDATE `Users`
                        SET 
                            us_lname = CASE WHEN us_lname <> ? THEN ? ELSE us_lname END,
                            us_fname = CASE WHEN us_fname <> ? THEN ? ELSE us_fname END,
                            us_mname = CASE WHEN us_mname <> ? THEN ? ELSE us_mname END,
                            us_contact = CASE WHEN  us_contact <> ? THEN ? ELSE  us_contact END
                        WHERE us_id = (SELECT $parentIdColumn FROM `student information` WHERE st_stID = ?);
                    ";
                    
                    $names = name($parent['name']);
                    $data['updateParent']['value'] = [
                            $names[0], $names[0],
                            $names[1], $names[1],
                            $names[2], $names[2],
                            $student['contact'], $student['contact'],
                            $studentId  
                    ];

                    
                    insert($data['updateParent']);
                    
                }
                
                $data['selectGrade']['value'] = [$students['Handled']['grade level'], $students['Handled']['school year'], $studentId];
                list($checkGrade, $checkResult) = select($data['selectGrade']);  
                if(!$checkGrade){
                    
                    $data['checkStudent']['value'] = [$studentId]; 
                    list($pendingSuccess, $pendingResult) = select($data['checkStudent']);

                    $data['enrollStudent']['value'] = [$studentId, null, $students['Handled']['grade level'], $students['Handled']['section'], $students['Handled']['school year']];
                    list($checkSuccess, $recentEnrollid) = insert($data['enrollStudent']);
                    
                    if($checkSuccess){
                        if(isset($Output['Handled']['type']) && $Output['Handled']['type'] !== 'studentpending_Enroll')  $Output['Handled']['type'] = 'student_Enroll';

                        //setter if pending or not
                        if($pendingSuccess && $pendingResult[0]['check'] == 1){
                            foreach($pendingResult[0] as $key => $value){
                                if($key != 'check'){
                                    $Output['Student'][$student['lrn']][] = $value;
                                }
                                
                            }

                             //set status
                            $data['updateStudentstatus']['value'] = [$recentEnrollid];
                            insert($data['updateStudentstatus']); 
                            $Output['Handled']['type'] = 'studentpending_Enroll';
                        }   
                    } 
                    
                     $Output['Handled']['type'] = 'update_Enroll';
                }else{
                    $Output['Handled']['type'] = 'update_Enroll';
                }
                   

                    $Output['Handled']['grade level'] = $students['Handled']['grade level'];
                    $Output['Handled']['section'] = $students['Handled']['section'];
                    $Output['Handled']['school year'] = $students['Handled']['school year'];
                         
            
        } else {
             // insert new students and enrolled";
           // echo "Student not found!";
            $name = name($student['name']); 
            $data['insertStudent']['value'] = ['student', $name[0], $name[1], $name[2], $student['birth'],  $student['sex'], $student['province'], $student['municipal'], $student['barangay'], $student['purok']]; 
            list($checkSuccess,  $studentID) =  insert($data['insertStudent']);

            $name = name($student['father name']); 
            $data['insertParent']['value'] = ['father', $name[0], $name[1], $name[2], 'M', $student['contact']];
            list($checkSuccess, $fatherID)  =  insert($data['insertParent']);

            $name = name($student['mother name']); 
            $data['insertParent']['value'] = ['mother', $name[0], $name[1], $name[2], 'F', $student['contact']];
            list($checkSuccess, $motherID) =  insert($data['insertParent']);
             
            $name = name($student['guardian name']); 
            $data['insertParent']['value'] = ['guardian', $name[0], $name[1], $name[2], 'F', $student['contact']];
            list($checkSuccess, $guardianID) = insert($data['insertParent']);

            $data['updateStudentinfo']['value'] = [$fatherID, $motherID, $guardianID, $student['lrn'],  $student['tongue'], $student['ip'], $student['religion'], $studentID];
            insert($data['updateStudentinfo']);

            $data['enrollStudent']['value'] = [$studentID, null, $students['Handled']['grade level'], $students['Handled']['section'], $students['Handled']['school year']];
            insert($data['enrollStudent']);

            $Output['Handled']['type'] = 'student_Enroll';
            $Output['Handled']['grade level'] = $students['Handled']['grade level'];
            $Output['Handled']['section'] = $students['Handled']['section'];
            $Output['Handled']['school year'] = $students['Handled']['school year'];
       
         }
     }

 return [true, $Output];
}





function db_gradesStudents($grades){
  
   $Output = [];
    //Check handled
    if(!isset($grades['Handled']['grade level']) || !isset($grades['Handled']['section']) || !isset($grades['Handled']['school year'])){
        $Output['Handled']['type'] = 'handle_Grade';
        return [false, $Output];
    }

    //Check if excel and current batch 
    if(($grades['Handled']['grade level'] !== $grades['Details']['grade level']) 
       || ($grades['Handled']['section'] !== $grades['Details']['section']) 
       || ($grades['Handled']['school year'] !== $grades['Details']['school year'])){
       
        $Output['Handled']['type'] = 'notmatch_Grade';
        return [false, $Output];
    }
    

    foreach ($grades["Data"] as $id => $grade) {
  
        $data = [
            'selectStudentID' => [
                'query' => "SELECT en.`en_id`, en.en_stID FROM `enrollments` en 
                            INNER JOIN users us ON en.en_stID = us.us_id 
                            WHERE  us.us_lname = ? AND us.us_fname = ? AND us.us_mname = ?  AND en_grade = ? AND en_section = ? AND en_shoolyear = ?",
                'bind'  => "ssssss",
                'value' => []  
            ],

            'selectStudentsubject' => [
                'query' => "SELECT `sf_id`  FROM `sf 1 / 10` WHERE sf_name = ?;",
                'bind'  => "s",
                'value' => []  
            ],

            'selectStudentgrade' => [
                'query' => "SELECT 1 FROM `student grades` sg 
                            WHERE sg_enID = ? AND sg_sfID = ? AND sg_quarter = ?",
                'bind'  => "iis",
                'value' => []  
            ],

            
            'insertStudentgrade' => [
                'query' => "INSERT INTO `student grades`(`sg_enID`, `sg_sfID`, `sg_quarter`, `sg_grade`) 
                                   VALUES (?, ?, ?, ?);",
                'bind'  => "iisi",
                'value' => []  
            ],


            
            'updateStudentgrade' => [
                'query' => "UPDATE `student grades` 
                        SET 
                        sg_grade = CASE WHEN sg_grade <> ? THEN ? ELSE sg_grade END
                        WHERE sg_enID = ?  AND sg_sfID = ?  AND sg_quarter = ?",
                'bind' => "iiiis",
                'value' => [] 
            ],

            'checkStudent' => [
                'query' => "SELECT 
                                1 AS 'check'
                            FROM 
                                `enrollments` en
                            LEFT JOIN
                                `student grades` sg ON en.en_id = sg.sg_enID
                            WHERE 
                                (sg.sg_grade = 0 OR sg.sg_enID IS NULL)
                                AND en.en_id = ?;
                            ",
            'bind'  => "i",
            'value' => []  
        ],

        'updateStudentstatus' => [
            'query' => "UPDATE 
                            `enrollments` en
                        SET 
                            en.`en_status` = 'Enrolled' 
                        WHERE 
                            en.en_status = 'Pending' 
                            AND en.en_stID = ?;
                        ",
            'bind' => "i",
            'value' => []
        ],

           
        ];

         $name = name($grade['Name']); 
         $data['selectStudentID']['value'] = [$name[0], $name[1], $name[2], $grades['Handled']['grade level'], $grades['Handled']['section'], $grades['Handled']['school year']]; 
         list($checkSuccess, $studentID) = select($data['selectStudentID']);
         
         if($checkSuccess){
            foreach ($grade as $subject => $scores) {
                if ($subject != "Name") { 
                     $data['selectStudentsubject']['value'] = [$subject]; 
                     list($checkSuccess, $subjectID) = select($data['selectStudentsubject']);
                    
                     foreach ($scores as $quarter => $score) {
                        $data['selectStudentgrade']['value'] = [$studentID[0]['en_id'], $subjectID[0]['sf_id'], $quarter]; 
                        list($checkSuccess, $gradeID) = select($data['selectStudentgrade']);

                        if($checkSuccess){
                             // upade grade";
                             if($score !== 0 && $score !== null){
                                 $data['updateStudentgrade']['value'] = [$score, $score, $studentID[0]['en_id'], $subjectID[0]['sf_id'], $quarter ];
                                 insert($data['updateStudentgrade']); 
                             }    
                        }else{
                            // insert grade";
                             $data['insertStudentgrade']['value'] = [$studentID[0]['en_id'], $subjectID[0]['sf_id'], $quarter, $score ]; 
                             insert($data['insertStudentgrade']);
                            
                        }

                     } 
  
                }
            }
             $data['checkStudent']['value'] = [$studentID[0]['en_id']]; 
             list($pendingSuccess, $pendingResult) = select($data['checkStudent']);
             
             if(!isset($pendingResult[0]['check'])){
                    $data['updateStudentstatus']['value'] = [$studentID[0]['en_stID']];
                    insert($data['updateStudentstatus']); 
             }

             $Output['Handled']['type'] = 'update_Grade';
         }
     
    }

 
    return [true,  $Output];
}






?>