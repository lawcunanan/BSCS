<?php 
require "../../../model/function.php";

//teacher
function teacherhandle() {
    global $data;
    $schoolyear = ''; 

    $output = display_all(
        $data['teacher']['display'],
        null,
        $output = function ($row = null, $id = null) use (&$schoolyear) {
            $schoolyear = $row['en_shoolyear'];
            return (
                "<tr>
					<td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
					<td>Grade " . htmlspecialchars($row['en_grade']) . "</td>
					<td>" . htmlspecialchars($row['en_section']) . "</td>
				</tr>"
            );
        }
    );
    
   
    return [$schoolyear, $output];
}

function username() {
    global $data;
    return display_all(
        $data['teacher']['name'],
        null,
        function ($row = null, $id = null) {
          
            if ($row && isset($row['name'])) {
                return "<b>{$row['name']}!</b>"; 
            }
            return ""; 
        }
    );
}




//teacher_advisory
function gradesubmission() {
    global $data;
    global $teacher;
    $school_year = '';
    $output = display_all(
                $data['teacher_advisory']['display'],
                $teacher,
                $output = function ($row = null, $id = null) use (&$school_year) {
                    $school_year = $row['en_shoolyear'];
                    $gradeSubmissionMessage = !empty($row['date']) 
                        ? "<i>Grades submitted on " . htmlspecialchars($row['date']) . "</i>" 
                        : "<i>Awaiting grades submission</i>";

                    return (
                        "<tr>
                            <td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
                            <td>Grade " . htmlspecialchars($row['en_grade']) . "</td>
                            <td>" . htmlspecialchars($row['en_section']) . "</td>
                            <td>" . htmlspecialchars($row['sg_quarter']) . "</td>
                            <td>" . $gradeSubmissionMessage . "</td>
                            <td>
                                <button type='button' class='btn btn-primary' 
                                    onclick=\"window.location.href='teacher_viewStudList.php?teacher=". urlencode($id) . "&grade=" . urlencode($row['en_grade']) . "&section=" . urlencode($row['en_section']) . "&school_year=" . urlencode($row['en_shoolyear']) . "'\">View Class List</button>
                                <button type='button' class='btn btn-success' 
                                    onclick=\"window.location.href='teacher_submitGrades.php?teacher=". urlencode($id) . "&grade=" . urlencode($row['en_grade']) . "&section=" . urlencode($row['en_section']) . "&school_year=" . urlencode($row['en_shoolyear']) . "'\">Submit Grades</button>
                            </td>
                        </tr>"
                    );
                }
            );

    return [$school_year, $output];
}



function gradesubmissiondeadline() {
    global $data;
    $school_year = '';
    $output = display_all(
        $data['teacher_advisory']['schoolyear'],
        null,
        $output = function ($row = null, $id = null) use (&$school_year) {
            
            $school_year = $row['ev_title'];
            if (!empty($row['submission_sdate'])) {
                return (
                    '<span class="message mb-3"><b>Reminder:</b> The deadline for grade submission is until <u>' 
                    . htmlspecialchars($row['submission_sdate']) . '</u> for ' 
                    . htmlspecialchars($row['quarter']) . '.</span>'
                );
            } else {
                
                return '';
            }
        }
    );

    return [$school_year, $output];
}




//teacher_submitgrade()
function previewgrades(){
    return modal('Class Grades Preview', 
            'modal-xl', 
            '<div class="modal-body">
              <div style="display: flex; gap:50px">
                <h5><b>School Year:</b> <span id="schoolYear2"></span></h5>
                <h5><b>Grade Level:</b> <span id="gradeLevel2"></span></h5>
                <h5><b>Section:</b> <span id="section2"></span></h5>
             </div>
             <br/>
             <div  id="gradesPreviewContent"></div>
            
            </div>', 
            'gradesExcel', 
            'Upload', 
            0);
}



function previewgrades1(){
    return modal('Student Grades Preview', 
            'modal-xl', 
            '<div class="modal-body">
              <div style="display: flex; gap:50px">
                <h5><b>School Year:</b> <span id="schoolYear2"></span></h5>
                <h5><b>Grade Level:</b> <span id="gradeLevel2"></span></h5>
                <h5><b>Section:</b> <span id="section2"></span></h5>
             </div>
             <br/>
             <div  id="gradesPreviewContent"></div>
            
            </div>', 
            'gradesExcel', 
            'Update_Grades', 
            0);
}




//teacher_submitgrades
function headersf10() {
    global $data;
    $header = [];

    display_all(
        $data['teacher_submitgrades']['headersf10'],
        null,
        $output = function ($row = null, $id = null) use (&$header) {
            $header[] = $row['subjects'];
        }
    );

    return $header;
}



function perquarter() {
    global $data;
    global $teacher;


    $output = display_all(
        $data['teacher_submitgrades']['perquarter'],
        $teacher,
        $output = function ($row = null, $id = null) {
            global $grade, $section, $school_year;
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['sg_quarter']) . "</td>
                    <td>
                        <div class='input-wrapper'>
                            <input
                                type='text'
                                value='" . htmlspecialchars($row['quarterly']) . "'
                                class='grade-input form-control'
                                disabled
                            />
                        </div>
                    </td>
                    <td>
                        <button
                            type='button'
                            class='btn btn-primary'
                            onclick=\"window.location.href='teacher_viewStudGrades.php?teacher=" . urlencode($id) . "&enroll=" . htmlspecialchars($row['en_id']) . "&grade=" . urlencode($grade) . "&section=" . urlencode($section) . "&school_year=" . urlencode($school_year) . "'\">
                            View
                        </button>
                    </td>
                </tr>"
            );
        }
    );

    
    if ($output == '') {
        $output = "<tr><td colspan='6'>No record found</td></tr>";
    }

    return $output;
}



function generalave() {
    global $data;
    global $teacher;

    $output = display_all(
        $data['teacher_submitgrades']['generalave'],
        $teacher,
        $output = function ($row = null, $id = null) {
            global $grade, $section, $school_year;
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['gender']) . "</td>
                    <td>
                        <div class='input-wrapper'>
                            <input
                                type='text'
                                value='" . htmlspecialchars($row['average']) . "'
                                class='grade-input form-control'
                                disabled
                            />
                        </div>
                    </td>
                   <td>" . implode(', ', array_unique(array_map('trim', explode(',', htmlspecialchars($row['quarterused']))))) . "</td>
                    <td>
                        <button
                            type='button'
                            class='btn btn-primary'
                            onclick=\"window.location.href='teacher_viewStudGrades.php?teacher=" . urlencode($id) . "&enroll=" . htmlspecialchars($row['en_id']) . "&grade=" . urlencode($grade) . "&section=" . urlencode($section) . "&school_year=" . urlencode($school_year) . "'\">
                            View
                        </button>
                    </td>
                </tr>"
            );
        }
    );


    if ($output == '') {
        $output = "<tr><td colspan='6'>No record found</td></tr>";
    }

    return $output;
}




//teacher_viewstudlist
function studentlist() {
    global $data;
    global $teacher;

    $output = display_all(
        $data['teacher_viewstudlist']['display'],
        $teacher,
        $output = function ($row = null, $id = null) {
            $remarks = 'NA';
            $average = 'NA';
            if(is_numeric($row['general_average']) && $row['general_average'] != null){
                $remarks = ($row['general_average'] >= 75) ? 'Passed' : 'Failed';
                $average = round($row['general_average'], 2);
            }
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                    <td>" . htmlspecialchars($average) . "</td>
                    
                    <td>{$remarks}</td>
                    <td>
                        <button
                            type='button'
                            class='btn btn-primary'
                            onclick=\"window.location.href='teacher_viewStudInfo.php?teacher=" . urlencode($id) . "&student=" . htmlspecialchars($row['us_id']) . "'\">
                            View Student
                        </button>
                    </td>
                </tr>"
            );
        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='6'>No record found</td></tr>";
    }

    return $output;
}




//studentinformation
function studentinfo() {
    global $data;

    return display_all(
        $data['teacher_studentinformation']['display'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td><b>LRN</b></td>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                </tr>
                <tr>
                    <td><b>Name</b></td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                </tr>
                <tr>
                    <td><b>Sex</b></td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                </tr>
                <tr>
                    <td><b>Birth Date</b></td>
                    <td>" . date('F j, Y', strtotime($row['birth'])) . "</td>
                </tr>
                <tr>
                    <td><b>Age as of " . htmlspecialchars($row['current_date']) . "</b></td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                </tr>
                <tr>
                    <td><b>Mother Tongue</b></td>
                    <td>" . htmlspecialchars($row['st_mothertongue']) . "</td>
                </tr>
                <tr>
                    <td><b>IP (Ethnic Group)</b></td>
                    <td>". htmlspecialchars($row['st_ip']) . "</td>
                </tr>
                <tr>
                    <td><b>Religion</b></td>
                    <td>" . htmlspecialchars($row['st_religion']) . "</td>
                </tr>
                <tr>
                    <td><b>Address</b></td>
                    <td>" . htmlspecialchars($row['address']) . "</td>
                </tr>
                <tr>
                    <td><b>Father's Name</b></td>
                    <td>" . htmlspecialchars($row['father']) . "</td>
                </tr>
                <tr>
                    <td><b>Mother's Maiden Name</b></td>
                    <td>" . htmlspecialchars($row['mother']) . "</td>
                </tr>
                <tr>
                    <td><b>Guardian</b></td>
                    <td>" . htmlspecialchars($row['guardian']) ."</td>
                </tr>
                <tr>
                    <td><b>Contact Number of Parent or Guardian</b></td>
                    <td>" . htmlspecialchars($row['st_contact']) ."</td>
                </tr>"
            );
        }
    );
}



function studentclasshistory() {
    global $data;
    global $teacher;
    return display_all(
        $data['teacher_studentinformation']['classdisplay'],
        $teacher,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
                    <td>" . htmlspecialchars($row['en_grade']) . "</td>
                    <td>" . htmlspecialchars($row['en_section']) . "</td>
                    <td>
                        <button
                            type='button'
                            class='btn btn-primary'
                            onclick=\"window.location.href='teacher_viewStudGrades.php?teacher=". urlencode($id) . "&enroll=" . htmlspecialchars($row['en_id']) . "&grade=" . urlencode($row['en_grade']) . "&section=" . urlencode($row['en_section']) . "&school_year=" . urlencode($row['en_shoolyear']) . "'\">
                            View Information
                        </button>
                    </td>
                </tr>"
            );
        }
    );
}




//teacher_viewstudentgrades
function studentinfor() {
    global $data;

    return display_all(
        $data['teacher_viewstudentgrades']['display'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td><b>LRN</b></td>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                </tr>
                <tr>
                    <td><b>Name</b></td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                </tr>
                <tr>
                    <td><b>Sex</b></td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                </tr>
                <tr>
                    <td><b>Age</b></td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                </tr>
                <tr>
                    <td><b>Rank</b></td>
                    <td>" . htmlspecialchars($row['rank']) . "</td>
                </tr>"
            );
        }
    );
}


function perquarterpreview() {
    global $data;
    $average = [
         "1" => [0, 0],
         "2" => [0, 0],
    ];
    $output = ''; $condi = true;
    $output = display_all(
        $data['teacher_viewstudentgrades']['perquarters'],
        null,
        $output = function ($row = null, $id = null) use (&$average, $condi) {
            if(is_numeric($row['grade']) && $row['grade'] !== null && $condi && $row['grade'] !== '-'){
               
                if (in_array($row['subject_name'], ['Music', 'Arts', 'PE', 'Health'])) {
                     $average['2'][0] += (float) $row['grade'];
                     $average['2'][1]++;
                }else{
                     $average['1'][0] += (float) $row['grade'];
                     $average['1'][1]++;
                }
            }else{
                $average = [
                    "1" => [0, 0], 
                    "2" => [0, 0],
                ];
                $condi = false;
            }

            return (
                "<tr>
                    <td>" . htmlspecialchars($row['subject_name']) . "</td>
                    <td>" . htmlspecialchars($row['grade']) . "</td>
                </tr>
                "
            );
        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='2'>No record found</td></tr>";
    }else {
        if($condi &&  $average['1'][1] >= 1){  
            $mapeh = 0;
            if($average['2'][1] >= 1){
                $mapeh = (average($average['2'][0],$average['2'][1])); $average['1'][1]++; 
            }

            $output .= (
                    "<tr>
                        <td colspan='6'><strong>Quarter Average</strong> " . htmlspecialchars(average(($average['1'][0] + $mapeh),  $average['1'][1])) . "</td>
                    </tr>"
                );
        }
    }


    return $output;
}



function quarterpreview() {
    global $data;
    $average = [
         "1" => [0, 0],
         "2" => [0, 0],
    ];
    $output = ''; $condi = true;
    $output .= display_all(
        $data['teacher_viewstudentgrades']['allquarters'],
        null,
        $displayRow = function ($row = null, $id = null) use (&$average,  &$condi) {
            
            if(is_numeric($row['average']) && $row['average'] !== null && $condi){
                if (in_array($row['Subject'], ['Music', 'Arts', 'PE', 'Health'])) {
                     $average['2'][0] += (float) $row['average'];
                     $average['2'][1]++;
                }else{
                     $average['1'][0] += (float) $row['average'];
                     $average['1'][1]++;
                }
            }else{
                 $average = [
                    "1" => [0, 0], 
                    "2" => [0, 0],
                ];
                $condi = false;
            }
            
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['Subject']) . "</td>
                    <td>" . htmlspecialchars($row['1st Quarter']) . "</td>
                    <td>" . htmlspecialchars($row['2nd Quarter']) . "</td>
                    <td>" . htmlspecialchars($row['3rd Quarter']) . "</td>
                    <td>" . htmlspecialchars($row['4th Quarter']) . "</td>
                     <td>" . htmlspecialchars($row['average']) . "</td>
                </tr>"
            );
        }
    );

    if($condi && $average['1'][1] >= 1){           
        $mapeh = (average($average['2'][0],$average['2'][1])); $average['1'][1]++;       
        $output .= (
                 
                "<tr>
                    <td colspan='6'><strong>General Average</strong> " . htmlspecialchars(average(($average['1'][0] + $mapeh),  $average['1'][1])) . "</td>
                </tr>"
        );
    }
    return $output; 
}

function quarter($num) {
   
    switch ($num) {
        case 1:
            return "1st Quarter";
        case 2:
            return "2nd Quarter";
        case 3:
            return "3rd Quarter";
        case 4:
            return "4th Quarter";
        default:
            return "Invalid quarter"; 
    }
}




//teacher_handled
function handledclass() {
    global $data;
    global $teacher;

    return display_all(
        $data['teacher_handled']['handledclass'],
        $teacher,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
                    <td>" . htmlspecialchars($row['en_grade']) . "</td>
                    <td>" . htmlspecialchars($row['en_section']) . "</td>
                    <td>
                        <button type='button' class='btn btn-primary' 
                            onclick=\"window.location.href='teacher_viewStudList.php?teacher=". urlencode($id) . "&grade=" . urlencode($row['en_grade']) . "&section=" . urlencode($row['en_section']) . "&school_year=" . urlencode($row['en_shoolyear']) . "'\">
                        View Class List</button>
                    </td>
                </tr>"
            );
        }
    );
}



function schoolyear() {
    global $data;

    return display_all(
        $data['teacher_handled']['schoolyear'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<option value = '". htmlspecialchars($row['en_shoolyear']) ."'>". htmlspecialchars($row['en_shoolyear']) ."</option>"
            );
        }
    );
}



function gradelevel() {
    global $data;

    return display_all(
        $data['teacher_handled']['gradelevel'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<option value = '". htmlspecialchars($row['en_grade']) ."'>Grade ". htmlspecialchars($row['en_grade']) ."</option>"
            );
        }
    );
}




//teacher_generate
function generatedoc() {
    global $data;
    GLOBAL $teacher;
    $output = display_all(
        $data['teacher_generate']['display'],
        $teacher,
        $output = function ($row = null, $id = null) {

            $value = implode("|", [
                $row['en_id'],  
                $row['name'],  
                $row['en_grade'],
                $row['en_section'],
                $row['en_shoolyear']
            ]);
            $prev = json_encode([$row["us_id"], $row["en_id"], $row["en_verify"]]);
            
             $update = '';
             if($row['en_verify'] === 'Unverified'){
                $update = "<button type='button' name = 'btn_Upadate' class='btn btn-outline-primary' value = '" . htmlspecialchars($row['us_id']) . "' data-bs-toggle='modal' data-bs-target='#updategrades" . htmlspecialchars($row['us_id']) . "'>
                                <i class=fa fa-file></i> Update 
                            </button>";
             }
            
            return ( 
                "<tr>   
                    <td>
                        <form method = 'POST'> 
                            <button type='submit' name='btn_Preview' class='btn btn-primary' value='{$prev}'>
                                Preview
                            </button>
                            {$update}
                        </form>
                    </td>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                    <td><i style = 'font-size:14px;'>Grade " . htmlspecialchars($row['en_grade']) . "  grades for " . htmlspecialchars($row['en_shoolyear']) . " are " . htmlspecialchars($row['en_verify']) . ".</i></td>
                </tr>"
            ) .modal(
                    "Update Grades", 
                    "",  
                    "<div class='modal-body body1'>
                        
                        <div style='display: flex; gap:50px'>
                            <h5><b>School Year:</b> <span >" . htmlspecialchars($row['en_shoolyear']) . "</span></h5>
                            <h5><b>Grade Level:</b> <span >" . htmlspecialchars($row['en_grade']) . "</span></h5>
                            <h5><b>Section:</b> <span>" . htmlspecialchars($row['en_section']) . "</span></h5>
                        </div>
                        <div style='margin-top: 10px;'>
                            <label for='formFile'>Upload Grades</label>
                            <input
                                class='form-control'
                                type='file'
                                name='upload_excel' 
                                accept='.xls,.xlsx'
                                required
                            />
                        </div>
                    </div>", 
                    "updategrades" . htmlspecialchars($row['us_id']) . "", 
                    "Update",
                    $value
                );

        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='7'>No record found</td></tr>";
    }

    return $output;
}



//sf10
function sfDocumentPreview() {
    global $data;

    return display_all(
        $data['teacher_generate']['sf_information'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                '
                <div class="document-preview container-preview sf">
                    <header class="header-sf">
                        <div class="logo-kagawaran">
                            <img src="../../../assets/images/kagawaran.png" alt="">
                        </div>
                        <div class="information">
                            <h6>Republic of the Philippines</h6>
                            <h6>Department of Education</h6>
                            <h4>Learner Permanent Record for Elementary School (SF10-ES)</h4>
                            <i>(Formerly Form 137)</i>
                        </div>
                        <div class="logo-deped">
                            <img src="../../../assets/images/deped.png" alt="">
                        </div>
                    </header>
                    <table class="tab-personalInformation">
                        <thead>
                            <tr>
                                <th colspan="8" style="text-align: center; font-size: 11px;">LEARNER\'S PERSONAL INFORMATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="label">LAST NAME:</td>
                                <td class="value" style="width: 130px;">' . htmlspecialchars($row['us_lname']) . '</td>
                                <td class="label">FIRST NAME:</td>
                                <td class="value" style="width: 130px;">' . htmlspecialchars($row['us_fname']) . '</td>
                                <td class="label">NAME EXTN.(Jr,I,II):</td>
                                <td class="value" style="width: 39px;"></td>
                                <td class="label">MIDDLE NAME:</td>
                                <td class="value" style="width: 130px;">' . htmlspecialchars($row['us_mname']) . '</td>
                            </tr>
                            <tr>
                                <td class="label">Learner Reference Number(LRN)</td>
                                <td class="value" style="width: 199px;">' . htmlspecialchars($row['st_lrn']) . '</td>
                                <td class="label">Birthdate (mm/dd/yy)</td>
                                <td class="value" style="width: 199px;">' . htmlspecialchars($row['us_birthday']) . '</td>
                                <td class="label">Sex</td>
                                <td class="value" style="width: 67px;">' . htmlspecialchars($row['us_gender']) . '</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="tab-eligibility">
                        <thead>
                            <tr>
                                <th colspan="8" style="text-align: center; font-size: 11px;">ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border: 1px solid black; border-top: none; border-bottom: none; padding: 5px;">
                                <td class="crid" style="margin-right: 50px;">Credential Presented for Grade 1:</td>
                                <td class="crid" style="margin-right: 50px;">
                                    <input type="checkbox" class="square-button"> Kinder Progress Report
                                </td>
                                <td class="crid" style="margin-right: 50px;">
                                    <input type="checkbox" class="square-button"> ECCD Checklist
                                </td>
                                <td class="crid">
                                    <input type="checkbox" class="square-button"> Kindergarten Certificate of Completion
                                </td>
                            </tr>
                            <tr style="border: 1px solid black; border-top: none; padding: 5px;">
                                <td class="label">Name of School:</td>
                                <td class="value" style="width: 210px;" contenteditable="true"></td>
                                <td class="label">School ID:</td>
                                <td class="value" style="width: 85px;" contenteditable="true"></td>
                                <td class="label">Address of School:</td>
                                <td class="value" style="width: 210px;" contenteditable="true"></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px; margin-top: 5px;">Other Credential Presented</td>
                            </tr>
                            <tr class="lower" style="margin-left: 40px;">
                                <td class="label">
                                    <input type="checkbox" class="square-button"> PEPT Passer Rating:
                                </td>
                                <td class="value" style="width: 65px;" contenteditable="true"></td>
                                <td class="label">Date of Examination Assessment(mm/dd/yy):</td>
                                <td class="value" style="width: 65px;" contenteditable="true"></td>
                                <td class="label">
                                    <input type="checkbox" class="square-button"> Others (Pls Specify):
                                </td>
                                <td class="value" style="width: 136px;" contenteditable="true"></td>
                            </tr>
                            <tr class="lower" style="margin-left: 40px;">
                                <td class="label">Name and Address of Testing Center:</td>
                                <td class="value" style="width: 251px;" contenteditable="true"></td>
                                <td class="label">Remark:</td>
                                <td class="value" style="width: 251px;" contenteditable="true"></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="tab-scholastic">
                        <thead>
                            <tr>
                                <th colspan="8" style="text-align: center; font-size: 11px;">SCHOLASTIC RECORD</th>
                            </tr>
                        </thead>
                    </table>

                    <section class="record-mainContainer">
                        ' . sf_schoolyear($row['us_id']) . '   
                    </section>
                </div>'
            );
        }
    );
}



function sf_schoolyear($id) {
    global $data;
   
    $data['teacher_generate']['sf_schoolyear']['value'] = [$id];
    return display_all(
        $data['teacher_generate']['sf_schoolyear'],
        null,
        $output = function ($row = null, $id = null) {
            
           
            return (
                '<div class="container">
                    <table class="tab-information">
                        <tbody>
                            <tr>
                                <td class="label">School:</td>
                                <td class="value" style="width: 200px;">'. htmlspecialchars($row['si_name']) .'</td>
                                <td class="label">School ID:</td>
                                <td class="value" style="width: 65px;">'. htmlspecialchars($row['si_schoolID']) .'</td>
                            </tr>
                            <tr>
                                <td class="label">District:</td>
                                <td class="value" style="width: 75px;">'. htmlspecialchars($row['si_district']) .'</td>
                                <td class="label">Division:</td>
                                <td class="value" style="width: 75px;">'. htmlspecialchars($row['si_division']) .'</td>
                                <td class="label">Region:</td>
                                <td class="value" style="width: 65px;">'. htmlspecialchars($row['si_region']) .'</td>
                            </tr>
                            <tr>
                                <td class="label">Classified as Grade:</td>
                                <td class="value" style="width: 20px;">'. htmlspecialchars($row['en_grade']) .'</td>
                                <td class="label">Section:</td>
                                <td class="value" style="width: 50px;">'. htmlspecialchars($row['en_section']) .'</td>
                                <td class="label">School Year:</td>
                                <td class="value" style="width: 70px;">'. htmlspecialchars($row['en_shoolyear']) .'</td>
                            </tr>
                            <tr>
                                <td class="label">Name of Adviser/Teacher:</td>
                                <td class="value" style="width: 110px;">'. htmlspecialchars($row['name']) .'</td>
                                <td class="label">Signature:</td>
                                <td class="value" style="width: 65px;"></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="tab-grade">
                        <thead>
                            <tr>
                                <th rowspan="2">LEARNING AREAS</th>
                                <th colspan="4">Quarterly Rating</th>
                                <th rowspan="2">Final Rating</th>
                                <th rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                            </tr>
                        </thead>
                        <tbody>
                            ' . sff_gradeLevel($row['en_id']) . '
                        </tbody>
                    </table>

                    <table class="tab-remedial">
                        <thead>
                            <tr>
                                <th>Remedial Classes</th>
                                <th colspan="4">Conduction to</th>
                            </tr>
                            <tr>
                                <td>Learning Areas</td>
                                <td>Final Rating</td>
                                <td>Remedial Class Mark</td>
                                <td>Recomputed Final Grade</td>
                                <td>Remarks</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>       
                                <td></td>    
                                <td></td>  
                                <td></td>  
                                <td></td>    
                            </tr>
                            <tr>
                                <td></td>       
                                <td></td>    
                                <td></td>  
                                <td></td>  
                                <td></td>    
                            </tr>
                        </tbody>
                    </table>
                </div>'
            );
        }
    );
}



function sff_gradeLevel($id) {
    global $data;

    $subject = [
        "Mother Tongue" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "English" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "Filipino" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "Mathematics" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "Science" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "AP" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "EPP" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "ESP" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "MAPEH" =>  ["1st" =>  [null, 0], "2nd" => [null, 0], "3rd" =>  [null, 0], "4th" =>  [null, 0], "final" =>  [null, 0]],
        "Music" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "Arts" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "PE" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "Health" => ["1st" => '', "2nd" => '', "3rd" => '', "4th" => '', "final" => ''],
        "General Average" => ["1st" =>  [null, 0], "2nd" => [null, 0], "3rd" =>  [null, 0], "4th" =>  [null, 0], "final" =>  [null, 0]],
    ];
    $data['teacher_generate']['sf_gradeLevel']['value'] = [$id];
    display_all(
        $data['teacher_generate']['sf_gradeLevel'],
        -1,
        $output = function ($row = null, $id = null) use (&$subject) {
            $subjectNames = array_keys($subject);
            foreach ($subjectNames as $subName) {
                if ($subName === $row['sf_name']) {
                    if($row['1st Quarter'] !== '-'){
                        $subject[$subName]['1st'] = $row['1st Quarter'];
                        $subject['General Average']['1st'][1]++;
                        $subject['General Average']['1st'][0] += $row['1st Quarter'];
                        
                        if (in_array($subName, ['Music', 'Arts', 'PE', 'Health'])) {
                            $subject['MAPEH']['1st'][1]++;
                            $subject['MAPEH']['1st'][0] += $row['1st Quarter'];
                            $subject['MAPEH']['final'][1]++;
                            $subject['MAPEH']['final'][0] +=  $row['1st Quarter'];
                        }
                    }

                   if($row['2nd Quarter'] !== "-"){
                        $subject[$subName]['2nd'] = $row['2nd Quarter'];
                        $subject['General Average']['2nd'][1]++;
                        $subject['General Average']['2nd'][0] += $row['2nd Quarter'];

                       if (in_array($subName, ['Music', 'Arts', 'PE', 'Health'])) {
                              $subject['MAPEH']['2nd'][1]++;
                              $subject['MAPEH']['2nd'][0] += $row['2nd Quarter'];
                              $subject['MAPEH']['final'][1]++;
                              $subject['MAPEH']['final'][0] +=  $row['2nd Quarter'];
                        }
                    }

                    if($row['3rd Quarter'] !== "-"){
                         $subject[$subName]['3rd'] = $row['3rd Quarter'];
                         $subject['General Average']['3rd'][1]++;
                         $subject['General Average']['3rd'][0] += $row['3rd Quarter'];

                        if (in_array($subName, ['Music', 'Arts', 'PE', 'Health'])) {
                              $subject['MAPEH']['3rd'][1]++;
                              $subject['MAPEH']['3rd'][0] += $row['3rd Quarter'];
                              $subject['MAPEH']['final'][1]++;
                              $subject['MAPEH']['final'][0] +=  $row['3rd Quarter'];
                        }
                    }

                    if($row['4th Quarter'] !== "-"){
                        $subject[$subName]['4th'] = $row['4th Quarter'];
                        $subject['General Average']['4th'][1]++;
                        $subject['General Average']['4th'][0] += $row['4th Quarter'];

                        if (in_array($subName, ['Music', 'Arts', 'PE', 'Health'])) {
                              $subject['MAPEH']['4th'][1]++;
                              $subject['MAPEH']['4th'][0] += $row['4th Quarter'];
                              $subject['MAPEH']['final'][1]++;
                              $subject['MAPEH']['final'][0] +=  $row['4th Quarter'];
                              
                        }
                    }
                    if($row['average'] !== 'NA'){
                       
                       if (!in_array($subName, ['Music', 'Arts', 'PE', 'Health'])) {
                         $subject[$subName]['final'] = $row['average'];
                         $subject['General Average']['final'][1]++;
                         $subject['General Average']['final'][0] += $row['average'];
                       }
                    } 
                }
            }

            return "";
        }
    );
    $mapeh = "";
    if($subject['MAPEH']['final'][1] >= 1){
        $mapeh = (round(($subject['MAPEH']['final'][0] / $subject['MAPEH']['final'][1]), 2));
        $subject['General Average']['final'][1]++;
        $subject['General Average']['final'][0] += $mapeh;
    }
     return $output = "
                <tr>
                    <td class='subject'>Mother Tongue</td>
                    <td>{$subject['Mother Tongue']['1st']}</td>
                    <td>{$subject['Mother Tongue']['2nd']}</td>
                    <td>{$subject['Mother Tongue']['3rd']}</td>
                    <td>{$subject['Mother Tongue']['4th']}</td>
                    <td>{$subject['Mother Tongue']['final']}</td>
                    <td>".checkIfPassed($subject['Mother Tongue']['final'])."</td>
                </tr>
                <tr>
                    <td class='subject'>English</td>
                    <td>{$subject['English']['1st']}</td>
                    <td>{$subject['English']['2nd']}</td>
                    <td>{$subject['English']['3rd']}</td>
                    <td>{$subject['English']['4th']}</td>
                    <td>{$subject['English']['final']}</td>
                    <td>".checkIfPassed($subject['English']['final'])."</td>
                </tr>
                <tr>
                    <td class='subject'>Filipino</td>
                    <td>{$subject['Filipino']['1st']}</td>
                    <td>{$subject['Filipino']['2nd']}</td>
                    <td>{$subject['Filipino']['3rd']}</td>
                    <td>{$subject['Filipino']['4th']}</td>
                    <td>{$subject['Filipino']['final']}</td>
                    <td>".checkIfPassed($subject['Filipino']['final'])."</td>
                </tr>

                <tr>
                    <td class='subject'>Mathematics</td>
                    <td>{$subject['Mathematics']['1st']}</td>
                    <td>{$subject['Mathematics']['2nd']}</td>
                    <td>{$subject['Mathematics']['3rd']}</td>
                    <td>{$subject['Mathematics']['4th']}</td>
                    <td>{$subject['Mathematics']['final']}</td>
                    <td>".checkIfPassed($subject['Mathematics']['final'])."</td>
                </tr>

                 <tr>
                    <td class='subject'>Science</td>
                    <td>{$subject['Science']['1st']}</td>
                    <td>{$subject['Science']['2nd']}</td>
                    <td>{$subject['Science']['3rd']}</td>
                    <td>{$subject['Science']['4th']}</td>
                    <td>{$subject['Science']['final']}</td>
                    <td>".checkIfPassed($subject['Science']['final'])."</td>
                </tr>

                 <tr>
                    <td class='subject'>Aralin Panlipunan</td>
                    <td>{$subject['AP']['1st']}</td>
                    <td>{$subject['AP']['2nd']}</td>
                    <td>{$subject['AP']['3rd']}</td>
                    <td>{$subject['AP']['4th']}</td>
                    <td>{$subject['AP']['final']}</td>
                    <td>".checkIfPassed($subject['AP']['final'])."</td>
                </tr>

                 <tr>
                    <td class='subject'>EPP / TLE</td>
                    <td>{$subject['EPP']['1st']}</td>
                    <td>{$subject['EPP']['2nd']}</td>
                    <td>{$subject['EPP']['3rd']}</td>
                    <td>{$subject['EPP']['4th']}</td>
                    <td>{$subject['EPP']['final']}</td>
                    <td>".checkIfPassed($subject['EPP']['final'])."</td>
                </tr>
                <tr>
                    <td class='subject'>Mapeh</td>
                    <td>". average($subject['MAPEH']['1st'][0] , $subject['MAPEH']['1st'][1])."</td>
                    <td>". average($subject['MAPEH']['2nd'][0] , $subject['MAPEH']['2nd'][1]) ."</td>
                    <td>". average($subject['MAPEH']['3rd'][0] , $subject['MAPEH']['3rd'][1]) ."</td>
                    <td>". average($subject['MAPEH']['4th'][0] , $subject['MAPEH']['4th'][1]) ."</td>
                    <td>{$mapeh}</td>
                    <td>".checkIfPassed($mapeh)."</td>
                </tr>
                <tr>
                    <td class='sub'>Music</td>
                    <td>{$subject['Music']['1st']}</td>
                    <td>{$subject['Music']['2nd']}</td>
                    <td>{$subject['Music']['3rd']}</td>
                    <td>{$subject['Music']['4th']}</td>
                    <td>{$subject['Music']['final']}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class='sub'>Arts</td>
                    <td>{$subject['Arts']['1st']}</td>
                    <td>{$subject['Arts']['2nd']}</td>
                    <td>{$subject['Arts']['3rd']}</td>
                    <td>{$subject['Arts']['4th']}</td>
                    <td>{$subject['Arts']['final']}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class='sub'>Physical Education</td>
                    <td>{$subject['PE']['1st']}</td>
                    <td>{$subject['PE']['2nd']}</td>
                    <td>{$subject['PE']['3rd']}</td>
                    <td>{$subject['PE']['4th']}</td>
                    <td>{$subject['PE']['final']}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class='sub'>Health</td>
                    <td>{$subject['Health']['1st']}</td>
                    <td>{$subject['Health']['2nd']}</td>
                    <td>{$subject['Health']['3rd']}</td>
                    <td>{$subject['Health']['4th']}</td>
                    <td>{$subject['Health']['final']}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class='subject'>Eduk sa Pagpapakatao</td>
                    <td>{$subject['ESP']['1st']}</td>
                    <td>{$subject['ESP']['2nd']}</td>
                    <td>{$subject['ESP']['3rd']}</td>
                    <td>{$subject['ESP']['4th']}</td>
                    <td>{$subject['ESP']['final']}</td>
                    <td>".checkIfPassed($subject['ESP']['final'])."</td>
                </tr>
                <tr>
                    <td class='sub'>*Arabic Language</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class='sub'>*Islamic Values Education</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class='subject'>General Average</td>
                    <td>". average($subject['General Average']['1st'][0] , $subject['General Average']['1st'][1]) ."</td>
                    <td>". average($subject['General Average']['2nd'][0] , $subject['General Average']['2nd'][1]) ."</td>
                    <td>". average($subject['General Average']['3rd'][0] , $subject['General Average']['3rd'][1]) ."</td>
                    <td>". average($subject['General Average']['4th'][0] , $subject['General Average']['4th'][1]) ."</td>
                    <td>". average($subject['General Average']['final'][0] , $subject['General Average']['final'][1]) ."</td>
                    <td>".checkIfPassed(average($subject['General Average']['final'][0] , $subject['General Average']['final'][1]))."</td>
                </tr>";
}



function checkIfPassed($grade) { 
    if ($grade >= 75) {
        return "Passed";
    } elseif($grade < 75 && $grade != 0  && $grade != '')   {
        return "Failed";
    }
}

































//teachercalendar
function calendarapprove() {
    global $data;

    $approveData = []; 
    display_all(
        $data['teachercalendar']['approve'],
        null,
        $output = function ($row = null, $id = null) use (&$approveData) {
            if (is_array($row)) {
              
                $approveData[] = [
                    "id" => $row['ev_id'],
                    "type" => $row['ev_type'],
                    "title" => $row['ev_title'],
                    "date" => $row['date'],
                    "time" => $row['time'],
                    "description" => $row['ev_description'],
                    "requestedby" => $row['name'],
                    "requastedon" => $row['requested'],
                ];
            }

          
            return ("");
        }
    );

    return $approveData;
}

function addevent() {
    return modal(
        'Add New Activity',
        '',
        '<div class="modal-body">
						
							<div class="mb-3">
								<label class="form-label">Title of Activity</label>
								<input
									type="text"
									class="form-control"
									id="eventTitle"
									required
                                    name = "ev_title"
								/>
							</div>
							<div class="mb-3">
								<label class="form-label">Type of Activity</label>
								<select class="form-select" id="eventType" required  name = "ev_type">
									<option value="School-wide">School-wide</option>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Start Date</label>
								<input
									type="date"
									class="form-control"
									id="eventStart"
									required
                                    min ="'.date('Y-m-d').'"
                                    name = "ev_sdate"
								/>
							</div>
							<div class="mb-3">
								<div class="form-check">
									<input
										class="form-check-input"
										type="checkbox"
										id="singleDayEvent"
										checked
									/>
									<label class="form-check-label" for="singleDayEvent">
										Single Day Activity
									</label>
								</div>
							</div>
							<div class="mb-3" id="endDateContainer" style="display: none">
								<label class="form-label">End Date</label>
								<input type="date" class="form-control" id="eventEnd"  min ="'.date('Y-m-d').'"  name = "ev_edate" />
							</div>
							
							<div class="time-inputs" >
								<div class="mb-3">
									<label class="form-label">Start Time</label>
									<input type="time" class="form-control" id="startTime"   name = "ev_stime" required/>
								</div>
								<div class="mb-3">
									<label class="form-label">End Time</label>
									<input type="time" class="form-control" id="endTime" name = "ev_etime" required/>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea
									class="form-control"
									id="eventDescription"
									rows="3"
                                    required
                                    name = "ev_description"
								></textarea>
							</div>
						
					</div>
        ',
        'addEventModal',
        'Save Event',
        0
    );
}



function calendardelete() {
    global $principal;

    return modal(
        "Delete Activity", 
        "",  
        "<div class='modal-body body1'>
            <h6>
                Are you sure you want to delete this activity?
            </h6>
        </div>", 
        "myDelete", 
        "Delete",
        0
    );
}



function calendarupcoming() {
    global $data;
  
    return display_all(
        $data['teachercalendar']['upcoming'],
        null,
        $output = function ($row = null, $id = null) use (&$count) {
            $count++;

            return ('
                <div class="event-card  ' . htmlspecialchars($row['ev_type']) . '">
                    <div class="event-type-badge  ' . htmlspecialchars($row['ev_type']) . '">
                        ' . htmlspecialchars($row['ev_type']) . ' Event
                    </div>
                    <div class="event-title"><span class = "label">Title:</span> ' . htmlspecialchars($row['ev_title']) . '</div>
                    <div class="event-datetime">
                        <div class="event-date">
                            <span class = "label">Date:</span> ' . htmlspecialchars($row['date']) . '
                        </div>
                        <div class="event-time">
                            <span class = "label">Time:</span> ' . htmlspecialchars($row['time']) . '
                        </div>
                        <hr>
                        <div class="event-time">
                            <span class = "label">Requested By:</span> ' . htmlspecialchars($row['name']) . '
                        </div>
                    </div>
                </div>'
            );
        }
    );

}


function calendarrejected() {
    global $data;

    return display_all(
        $data['teachercalendar']['rejected'],
        null,
        $output = function ($row = null, $id = null) use (&$count) {
            $count++;

            return ('
                <div class="event-card  ' . htmlspecialchars($row['ev_type']) . '">
                    <div class="event-type-badge  ' . htmlspecialchars($row['ev_type']) . '">
                        ' . htmlspecialchars($row['ev_type']) . ' Event
                    </div>
                    <div class="event-title"><span class = "label">Title:</span> ' . htmlspecialchars($row['ev_title']) . '</div>
                    <div class="event-title"><span class = "label">Reason:</span> ' . htmlspecialchars($row['ev_remarks']) . '</div>
                    <div class="event-title"><span class = "label">Requested By:</span> ' . htmlspecialchars($row['name']) . '</div>
                    <hr>
                    <div class="button-container">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventDetails_' . htmlspecialchars($row['ev_id']) . '">
                            Show Details
                        </button>
                    </div>
                    
                </div>'
            ) . modal(
                        'Event Details',
                        '',
                        '<div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Event Title</label>
                                                <input type="text" class="form-control" id="viewEventTitle" required value = "' . htmlspecialchars($row['ev_title']) . '" name = "ev_title"/>
                                            </div>
                                            <div style="display: flex; gap: 10px;">
                                                <div class="mb-3">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="viewEventStart" required min ="'.date('Y-m-d').'"  value = "' . htmlspecialchars($row['ev_sdate']) . '" name = "ev_sdate" />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="viewEventEnd" min ="'.date('Y-m-d').'"  value = "' . htmlspecialchars($row['ev_edate']) . '" name = "ev_edate" />
                                                </div>
                                            </div>
                                            <div style="display: flex; gap: 10px;">
                                                <div class="mb-3">
                                                    <label class="form-label">Start Time</label>
                                                    <input type="time" class="form-control" id="viewEventStart"  value = "' . htmlspecialchars($row['ev_stime']) . '" name = "ev_stime" required/>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">End Time</label>
                                                    <input type="time" class="form-control" id="viewEventEnd"  value = "' . htmlspecialchars($row['ev_etime']) . '" name = "ev_etime" required/>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea
                                                    class="form-control"
                                                    id="viewEventDescription"
                                                    rows="3"
                                                    required
                                                    name = "ev_description"
                                                >' . htmlspecialchars($row['ev_description']) . '</textarea>
                                            </div>
                                    </div>
                                    ',
                        'eventDetails_' . htmlspecialchars($row['ev_id']) . '',
                        'none',
                         htmlspecialchars($row['ev_id'])
                    );
        }
    );

}


function calendarpending() {
    global $data;
    $count = 0;
    
    $output = display_all(
        $data['teachercalendar']['pending'],
        null,
        $output = function ($row = null, $id = null) use (&$count) {
            $count++;
            return ('
                        <div class="event-card pending">
                            <div class="header-row">
                                <div class="event-type-badge  ' . htmlspecialchars($row['ev_type']) . '">
                                    ' . htmlspecialchars($row['ev_type']) . ' Event
                                </div>
                            </div>
                            
                            <table class="event-details-table">
                                <tr>
                                    <td class="label">Title of Activity:</td>
                                    <td class="value">' . htmlspecialchars($row['ev_title']) . '</td>
                                    <td class="label"><i class="fas fa-user"></i> Requested by:</td>
                                    <td class="value">' . htmlspecialchars($row['name']) . '</td>
                                </tr>
                                <tr>
                                    <td class="label">Date of Activity:</td>
                                    <td class="value">' . htmlspecialchars($row['date']) . '</td>
                                    <td class="label"><i class="far fa-clock"></i> Requested on:</td>
                                    <td class="value">' . htmlspecialchars($row['requested']) . '</td>
                                </tr>
                                <tr>
                                    <td class="label">Time of Activity:</td>
                                    <td class="value">' . htmlspecialchars($row['time']) . '</td>
                                </tr>
                                <tr>
                                    <td class="label description">Description:</td>
                                    <td class="value">' . htmlspecialchars($row['ev_description']) . '</td>
                                </tr>
                            </table>

                            <div class="button-container">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventDetails_' . htmlspecialchars($row['ev_id']) . '">
                                    Show Details
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#myDelete" onclick = "setID(\''.htmlspecialchars($row['ev_id']) .'\', \'btnDelete\')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>'
            ) . modal(
                        'Event Details',
                        '',
                        '<div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Event Title</label>
                                                <input type="text" class="form-control" id="viewEventTitle" required value = "' . htmlspecialchars($row['ev_title']) . '" name = "ev_title"/>
                                            </div>
                                            <div style="display: flex; gap: 10px;">
                                                <div class="mb-3">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="viewEventStart" required min ="'.date('Y-m-d').'"  value = "' . htmlspecialchars($row['ev_sdate']) . '" name = "ev_sdate" />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="viewEventEnd" min ="'.date('Y-m-d').'"  value = "' . htmlspecialchars($row['ev_edate']) . '" name = "ev_edate" />
                                                </div>
                                            </div>
                                            <div style="display: flex; gap: 10px;">
                                                <div class="mb-3">
                                                    <label class="form-label">Start Time</label>
                                                    <input type="time" class="form-control" id="viewEventStart"  value = "' . htmlspecialchars($row['ev_stime']) . '" name = "ev_stime" required/>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">End Time</label>
                                                    <input type="time" class="form-control" id="viewEventEnd"  value = "' . htmlspecialchars($row['ev_etime']) . '" name = "ev_etime" required/>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea
                                                    class="form-control"
                                                    id="viewEventDescription"
                                                    rows="3"
                                                    required
                                                    name = "ev_description"
                                                >' . htmlspecialchars($row['ev_description']) . '</textarea>
                                            </div>
                                    </div>
                                    ',
                        'eventDetails_' . htmlspecialchars($row['ev_id']) . '',
                        'Save',
                         htmlspecialchars($row['ev_id'])
                    );
            
        }
    );

    return [$count, $output];
}

?>