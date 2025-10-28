<?php 
require "../../../model/function.php";

//registrar
function currentNumber() {
    global $data;
    $schoolyear = ''; $ctr = 0;

    $output = display_all(
        $data['registrar']['display'],
        null,
        $output = function ($row = null, $id = null) use (&$schoolyear, &$ctr) {
            $schoolyear = $row['en_shoolyear'];
            $ctr += $row['number'];
            return (
                '<tr>
                    <td>' . htmlspecialchars($row['en_grade']) . '</td>
                    <td>' . htmlspecialchars($row['number']) . '</td>
                </tr>'
            );
        }
    );
    
    if($ctr > 0){
       $output .= "<tr><td><b>OVERALL</b></td><td><b>{$ctr}</b></td></tr>";
    }
    return [$schoolyear, $output];
}


function username() {
    global $data;
    return display_all(
        $data['registrar']['name'],
        null,
        function ($row = null, $id = null) {
          
            if ($row && isset($row['name'])) {
                return "<b>{$row['name']}!</b>"; 
            }
            return ""; 
        }
    );
}



//registrar_enroll
function registerclass() {
    global $data;
    global $registrar;
    
  
   return display_all(
                $data['registrar_enroll']['display'],
                $registrar,
                $output = function ($row = null, $id = null)  {
                   
                    $remarks = !empty($row['date']) 
                        ? "<i>Updated on " . htmlspecialchars($row['date']) . "</i>" 
                        : "<i>-</i>";
                    return (
                        "<tr>
                            <td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
                            <td>Grade " . htmlspecialchars($row['en_grade']) . "</td>
                            <td>" . htmlspecialchars($row['en_section']) . "</td>
                            <td>$remarks</td>
                            <td>
                                <button type='button' class='btn btn-primary' 
                                        onclick=\"window.location.href='registrar_manageEnrolled.php?registrar=" . 
                                        urlencode($id) . 
                                        "&grade=" . 
                                        urlencode($row['en_grade']) . 
                                        "&section=" . 
                                        urlencode($row['en_section']) . 
                                        "&school_year=" . 
                                        urlencode($row['en_shoolyear']) . 
                                        "'\">View Class List</button>
                            </td>
                        </tr>"
                    );
                }
            );

   
}

function registerSY() {
   global $data;
   global $registrar;
    
   return display_all(
                $data['registrar_enroll']['schoolyear'],
                $registrar,
                $output = function ($row = null, $id = null)  {
                  
                    return $row['year'];
                }
            );
}

function headerSF1() {
    global $data;

    return display_all(
        $data['registrar_enroll']['headersf1'],
        null,
        $output = function ($row = null, $id = null) {
            return $row['names'];
        }
    );
}

function headersf10() {
    global $data;
    $header = [];

    display_all(
        $data['registrar_enroll']['headersf10'],
        null,
        $output = function ($row = null, $id = null) use (&$header) {
            $header[] = $row['subjects'];
        }
    );

    return $header;
}


function previewenroll() {
    return modal('Class List Preview', 
        'custom', 
        '<div class="modal-body">
            <div style="display: flex; gap:50px">
                <h5><b>School Year:</b> <span id="schoolYear"></span></h5>
                <h5><b>Grade Level:</b> <span id="gradeLevel"></span></h5>
                <h5><b>Section:</b> <span id="section"></span></h5>
            </div>
            <br/>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Birthdate</th>
                        <th>Age</th>
                        <th>Ip</th>
                        <th>Religion</th>
                        <th>Mother Tongue</th>
                        <th>Father \'s Name</th>
                        <th>Mother \'s Name</th>
                        <th>Guardian \'s Name</th>
                        <th>Contact</th>
                        <th>Purok</th>
                        <th>Barangay</th>
                        <th>Municipal</th>
                        <th>Province</th>
                    </tr>
                </thead>
                <tbody id="tableContent"></tbody>
            </table>
        </div>', 
        'uploadExcel', 
        'Upload', 
        0
    );
}

function enrolledbacth() {
    return modal('New Enrolled Batch', 
        'modal-lg', 
        '<div class="modal-body">
         <h6 class = "label">Students Enrolled - Status: Pending</h6>
         <br>
            <div style="display: flex; gap:50px">
                <h5><b>School Year:</b> <span id="schoolYear1"></span></h5>
                <h5><b>Grade Level:</b> <span id="gradeLevel1"></span></h5>
                <h5><b>Section:</b> <span id="section1"></span></h5>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>Section</th>
                        <th>School year</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="tableContent1"></tbody>
            </table>
        </div>', 
        'pendingExcel', 
        'none', 
        0
    ); 
}



//registrar_manageenrolled
function manageenrolled() {
    global $data;
    global $registrar;

    $output = display_all(
        $data['registrar_manageenrolled']['display'],
        $registrar,
        $output = function ($row = null, $id = null) {
            
            
            $remove = !empty($row['status']) 
                ? "<button type='submit' class='btn btn-danger'  onClick=\"setID('" . htmlspecialchars($row['en_id']) . "', 'btnRemove')\" data-bs-toggle='modal' data-bs-target='#removeClass' >
                        Remove from Class
                   </button>" 
                : "";

            return (
                "<tr>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                    <td>
                       
                        <button
                            type='button'
                            class='btn btn-primary'
                            onclick=\"window.location.href='registrar_viewStudInfo.php?registrar=" . 
                            urlencode($id) . 
                            "&student=" . 
                            urlencode($row['us_id']) . "'\"
                        >
                            View Student
                        </button>
                            {$remove}
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
function removeclassModal(){
  return modal('Remove from Class', 
            '', 
            '   <div class="modal-body"> 
                    
                    <div style = "display:flex; gap:15px;">
                        <div style = "width: 100%"> 
                            <label>Format</label>
                            <select class="form-select type" id="reasonSelect" name="manage_reason" style="width: 100%;  font-size: 14px;" required>
                                    <option value="" disabled hidden selected>Select</option>
                                    <option value="Section">Transfer Section</option>
                                    <option value="Transferred School">Transfer school</option>
                                    <option value="Dropped">Drop</option>
                            </select> 
                        </div> 
                        <div id="availableSectionDiv"   style = "width: 100%; display:none;" >
                            <label>Available section</label>
                            <select class="form-select type" name = "manage_section" style="width: 100%;  font-size: 14px;" >
                                    '.section().'
                            </select>
                        </div> 
                        
                    </div> 
                    <div style = "display:flex; flex-direction:column; margin-top:10px">
                        <label>Reason</label>
                        <textarea
							rows="3"
                            required
                            name = "manage_remarks"
						></textarea>
                    </div>           
                 </div>', 
            'removeClass', 
            'Remove', 
            0);

}

function section() {
    global $data;

    return display_all(
        $data['registrar_manageenrolled']['listsection'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<option value='" . htmlspecialchars($row['en_section']) . "'>" . htmlspecialchars($row['en_section']) . "</option>"
            );
        }
    );
}




//registrarstudentinfo
function studentinfo() {
    global $data;

    return display_all(
        $data['registrarstudentinfo']['display'],
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

function studentDocuments($id) {
   global $data;
   
   $data['registrarstudentinfo']['downloadreq']['value'] = [$id];
   return display_all(
        $data['registrarstudentinfo']['downloadreq'],
        null,
        $output = function ($row = null, $id = null) {
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=\"" . $row['fo_name'] . "_$id.pdf\"");
            header("Content-Length: " . strlen($row['do_file']));

            echo $row['do_file']; 
            return ""; 
        }
    );
}


function studentrequirements() {
    global $data;

    return display_all(
        $data['registrarstudentinfo']['requirements'],
        null,
        $output = function ($row = null, $id = null) {
            
             $value = implode("|", [
                $row['name'],  $row['missing'],    
            ]);

            $btn = '';
            if ($row['custom_message'] == 'Submitted') {
                $btn = '
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#requpdate"
                        onClick="setID(\'' . htmlspecialchars($row['do_id']) . '\', \'btnUpdate Requirement\')"
 
                    >
                       <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        name="btnDownload"
                        value="' . htmlspecialchars($row['do_id']) . '"
                    >
                        <i class="fa-solid fa-download"></i>
                    </button>';
            } else {

                   $type = ($row['modal'] === 'sf10' && $row['missing'] === 'TRO') ? 'req' : $row['modal'];

                    $btn = '
                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#' . htmlspecialchars($type) . '"
                            onClick="setID(\'' . htmlspecialchars($row['fo_id']) . '\', \'btnUpload Requirement\'); 
                                    setID(\'' . htmlspecialchars($value) . '\', \'btnUpload Transcript\')"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </button>';

            }

            return "
            <tr>
                <td>" . htmlspecialchars($row['fo_name']) . "</td>
                <td>" . htmlspecialchars($row['custom_message']) . "</td>
                <td>
                    <form method='POST'>
                        {$btn}
                    </form>
                </td>
            </tr>
            ";
        }
    );
}



function displayModalsreq() {
    return "
        " . modal(
            "Upload Requirements", 
            "",  
            "<div class='modal-body body1'>
                <div>
                    <label for='formFile'>Document (PDF)</label>
                    <input
                        class='form-control'
                        type='file'
                        name='uploadReq' 
                        accept='application/pdf'
                        required
                    />
                </div>  
            </div>", 
            "req", 
            "Upload Requirement",
            0
        ) . "

        " . modal(
            "Update Requirements", 
            "",  
            "<div class='modal-body body1'>
                <div>
                    <label for='formFile'>Document (PDF)</label>
                    <input
                        class='form-control'
                        type='file'
                        name='updateReq' 
                        accept='application/pdf'
                        required
                    />
                </div>  
            </div>", 
            "requpdate", 
            "Update Requirement",
            0
        ) . "
        
        " . modal(
            "Upload Transcript of Records (Excel)", 
            "",  
            "<div class='modal-body body1'>
                <div style='margin-top: 10px;'>
                    <label for='formFile'>SF10 Document (.xls,.xlsx)</label>
                    <input
                        class='form-control'
                        type='file'
                        name='upload_excel' 
                        accept='.xls,.xlsx'
                        required
                    />
                </div>
            </div>", 
            "sf10", 
            "Upload Transcript",
            0
        ) . "

    ";
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

function studentclasshistory() {
    global $data;
    global $registrar;
    return display_all(
        $data['registrarstudentinfo']['classdisplay'],
        $registrar,
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
                            onclick=\"window.location.href='registrar_viewStudGrades.php?registrar=". urlencode($id) . "&enroll=" . htmlspecialchars($row['en_id']) . "&grade=" . urlencode($row['en_grade']) . "&section=" . urlencode($row['en_section']) . "&school_year=" . urlencode($row['en_shoolyear']) . "'\">
                            View Information
                        </button>
                    </td>
                </tr>"
            );
        }
    );
}


//registrarviewstudentsgrades
function studentinfor() {
    global $data;

    return display_all(
        $data['registrarviewstudentsgrades']['display'],
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
        $data['registrarviewstudentsgrades']['perquarters'],
        null,
        $output = function ($row = null, $id = null) use (&$average,&$condi) {
           if(is_numeric($row['grade']) && $row['grade'] !== null && $condi){
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
        $data['registrarviewstudentsgrades']['allquarters'],
        null,
        $displayRow = function ($row = null, $id = null) use (&$average, &$condi) {
            
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


//registrarstuddirectory
function studentlist() {
    global $data;
    global $registrar;

    $output = display_all(
        $data['registrarstuddirectory']['display'],
        $registrar,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
					<td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
					<td class='status'>" . htmlspecialchars($row['en_status']) . "</td>
                    <td class='status'>" . htmlspecialchars($row['en_remarks']) . "</td>
					<td>
					    <button
                            type='button'
                            class='btn btn-primary'
                            onclick=\"window.location.href='registrar_viewStudInfo.php?registrar=" . 
                            urlencode($id) . 
                            "&student=" . 
                            urlencode($row['us_id']) . "'\"
                        >
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


function status() {
    global $data;

    return display_all(
        $data['registrarstuddirectory']['status'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<option value = '". htmlspecialchars($row['en_status']) ."'>". htmlspecialchars($row['en_status']) ."</option>"
            );
        }
    );
}




//registrarclassarchives
function archives() {
    global $data;
    global $registrar;
    $output = '';
    $output = display_all(
        $data['registrarclassarchives']['display'],
        $registrar,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['en_shoolyear']) . "</td>
                    <td>" . htmlspecialchars($row['en_grade']) . "</td>
                    <td>" . htmlspecialchars($row['en_section']) . "</td>
                    <td>
                        <button type='button' class='btn btn-primary' 
                            onclick=\"window.location.href='registrar_viewStudList.php?registrar=". urlencode($id) . "&grade=" . urlencode($row['en_grade']) . "&section=" . urlencode($row['en_section']) . "&school_year=" . urlencode($row['en_shoolyear']) . "'\">
                        View Class List</button>
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


function schoolyear() {
    global $data;

    return display_all(
        $data['registrarclassarchives']['schoolyear'],
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
        $data['registrarclassarchives']['gradelevel'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<option value = '". htmlspecialchars($row['en_grade']) ."'>Grade ". htmlspecialchars($row['en_grade']) ."</option>"
            );
        }
    );
}


//registrar_viewstudlist
function viewstudlist() {
    global $data;
    global $registrar;
    

    $output = display_all(
        $data['registrar_viewstudlist']['display'],
        $registrar,
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
                            onclick=\"window.location.href='registrar_viewStudInfo.php?registrar=" . urlencode($id) . "&student=" . htmlspecialchars($row['us_id']) . "'\">
                            View Student
                        </button>
                    </td>
                </tr>"
            );
        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='7'>No record found</td></tr>";
    }

    return $output;
}



//registrar_generate
function generatedoc() {
    global $data;

    $output = display_all(
        $data['registrar_generate']['display'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td><input type='radio' name='gen_studentid' onclick='selectRow(this)' value='" . htmlspecialchars($row['us_id']) . "' required></td>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                </tr>"
            );

            
        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='7'>No record found</td></tr>";
    }

    return $output;
}

function generategradeverfify() {
    global $data;
    GLOBAL $teacher;
    $output = display_all(
        $data['registrar_generate']['verify'],
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


function genedocuments() {
    global $data;
    return display_all(
                $data['registrar_generate']['documentss'],
                null,
                $output = function ($row = null, $id = null) {
                    
                    $fo_text = $row['fo_text'];
                    $fo_text = replacePlaceholder('Student', $row['sname'], $fo_text);
                    $fo_text = replacePlaceholder('LRN', $row['st_lrn'], $fo_text);
                    $fo_text = replacePlaceholder('Grade Level', $row['en_grade'], $fo_text);
                    $fo_text = replacePlaceholder('Section', $row['en_section'], $fo_text);
                    $fo_text = replacePlaceholder('School Year', $row['en_shoolyear'], $fo_text);
                    $fo_text = replacePlaceholder('Adviser', $row['tname'], $fo_text);
                    $fo_text = replacePlaceholder('Principal', $row['pname'], $fo_text);
                    $fo_text = replacePlaceholder('GWA', $row['avg_grade'], $fo_text);
                    $fo_text = replacePlaceholder('Date', date('M d, Y'), $fo_text);
                    $avg_grade = $row['avg_grade'];

                    if ($avg_grade >= 98 && $avg_grade <= 100) {
                        $honor = "With Highest Honors";
                    } elseif ($avg_grade >= 95 && $avg_grade < 98) {
                        $honor = "With High Honors";
                    } elseif ($avg_grade >= 90 && $avg_grade < 95) {
                        $honor = "With Honors";
                    } else {
                        $honor = "No Honors";
                    }

                    $fo_text = replacePlaceholder('Rank', $honor, $fo_text);
                    return (
                           $fo_text
                    );
                }
            );
    
}

function replacePlaceholder($placeholder, $value, $text) {
    
    return preg_replace_callback("/\{" . preg_quote($placeholder, '/') . "\}/i", function ($matches) use ($value) {
        if (ctype_upper(trim($matches[0], '{}'))) {
            return strtoupper($value);
        } elseif (ctype_lower(trim($matches[0], '{}'))) {
            return strtolower($value);
        } else {
            return $value; 
        }
    }, $text);
}


function doctemplate() {
    global $data;
    $arr = [];

    display_all(
                $data['registrar_generate']['templatedocuments1'],
                null,
                $output = function ($row = null, $id = null)use (&$arr) {
                    return (
                           $arr[$row['fo_id']] = $row['fo_text']
                    );
                }
            );
    
    return $arr; 
}


function documents() {
    global $data;

    return display_all(
        $data['registrar_generate']['documents'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<option value='" . htmlspecialchars($row['fo_id']) . "'>" . htmlspecialchars($row['fo_name']) . "</option>"
            );
        }
    );
}


function documents1() {
    global $data;
   
    return display_all(
        $data['registrar_generate']['documents'],
        null,
        $output = function ($row = null, $id = null) {

            $fo_id = htmlspecialchars($row['fo_id'], ENT_QUOTES, 'UTF-8');
            
            if ($row['fo_name'] !== 'SF10 (Form 137)' || $row['fo_id'] !== 3) {
                return "<option value='1' onClick=\"setID('$fo_id', 'btnUpdate'); selectTemplate('$fo_id');\">" . htmlspecialchars($row['fo_name']) . "</option>";
            }
            return '';
        }
    );
}

function documents2() {
    global $data;
   
    return display_all(
        $data['registrar_generate']['documents'],
        null,
        $output = function ($row = null, $id = null) {
            $fo_id = htmlspecialchars($row['fo_id'], ENT_QUOTES, 'UTF-8');
            
            if ($row['fo_name'] !== 'SF10 (Form 137)' || $row['fo_id'] !== 3) {
                return "<option value='1' onClick=\"setID('$fo_id', 'btnDelete Template');\" >"  . htmlspecialchars($row['fo_name']) . "</option>";
            }
            return '';
        }
    );
}

function templatedelete() {

    return modal(
        "Delete Template", 
        "",  
        '<div class="modal-body body1">
             <select class="form-select type" id="docuTypee" name="docuTypedel" style="width: 100%;" required>
                 ' . documents2() . '
             </select>
         </div>', 
        "delTemplate", 
        "Delete Template",
        0
    );
}


function edittemplate(){
    return  modal('Template Design & Edit', 
    'custom1', 
    '   <div class="modal-body">               
            <div class="documentsModal">
                <div class="container-documentModal">
                    <div class="main">
                        <div class="document-preview container-preview">
                            <header class="header-gmoral">
                                <div class="img"> 
                                    <img src="../../../assets/images/kagawaran.png" alt="">
                                </div>
                                <h5>Republic of the Philippines</h5>
                                <h4>Department of Education</h4>
                                <h6>Region III – Central Luzon</h6>
                                <h6>Schools Division of the City of Baliwag</h6>
                                <h6><b>BALIWAG SOUTH CENTRAL SCHOOL</b></h6>
                                <h6>J. Buizon St., Sto. Cristo, City of Baliwag, Bulacan</h6>
                            </header>
                            <textarea name="certificateContent" id="certificateContent" style="display: none;">dsads</textarea>
                            <div id="preview">
                                <div id="editor" contenteditable="true">
                                </div>
                            </div>
                            <footer class="footer-gmoral">
                                <div class="img-deped">
                                    <img src="../../../assets/images/matatag.png" alt="">
                                </div>
                                <div class="school-logo">
                                    <img src="../../../assets/images/BSCS-logo.png" alt="">
                                </div>
                                <div class="location">
                                    <h6><b>BALIWAG SOUTH CENTRAL SCHOOL</b></h6>
                                    <h6>J. Buizon St., Sto. Cristo, City of Baliwag, Bulacan</h6>
                                    <h6>E-mail Address: 104750@deped.gov.ph</h6>
                                </div>
                            </footer>
                        </div> 
                    </div>
                </div>
                <div class="container-toolsModal">
                    <div style="display: flex; justify-content:space-between; align-items: center;">
                            <h3>Format</h3>
                           <a href="#" class="trash" data-bs-toggle="modal" data-bs-target="#delTemplate">
                                <i class="fa-solid fa-trash" style="cursor: pointer;"></i>
                                <span>Delete</span>
                            </a>
                    </div>
                    <select class="form-select type" id="docuTypee" name="docuType" style="width: 100%;" required onchange="NewTemplate()">
                        <option value="newTemplate" >New Template</option>
                        "'.documents1().'"
                    </select>
                    <input type="text" id="newTemplate" class="search-input type" placeholder="Name of the template" name = "newtemp">
                    <h3>Tool</h3>
                    <div class="tool">
                        <button type="button" class="btn btn-primary" onclick="document.execCommand(\'bold\');"><strong>B</strong></button>
                        <button type="button" class="btn btn-primary" onclick="document.execCommand(\'italic\');"><em>I</em></button>
                        <button type="button" class="btn btn-primary" onclick="document.execCommand(\'underline\');"><u>U</u></button>
                        <select id="fontSize" class="form-control btn" style="width: auto;" onchange="changeFontSize()">
                            <option value="6pt">6pt</option>
                            <option value="8pt">8pt</option>
                            <option value="10pt">10pt</option>
                            <option value="12pt">12pt</option>
                            <option value="14pt">14pt</option>
                            <option value="16pt" selected>16pt</option>
                            <option value="18pt">18pt</option>
                            <option value="20pt">20pt</option>
                            <option value="24pt">24pt</option>
                            <option value="30pt">30pt</option>
                            <option value="36pt">36pt</option>
                            <option value="48pt">48pt</option>
                            <option value="72pt">72pt</option>
                        </select>
                        <button type="button"  class="btn btn-primary" onclick="toggleCase(\'uppercase\')">Uppercase</button>
                        <button type="button"  class="btn btn-primary" onclick="toggleCase(\'lowercase\')">Lowercase</button>
                    </div>

                    <h3>Student Details</h3>
                    <div class="details">
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{LRN}\')">{LRN}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Student}\')">{student}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{School Year}\')">{School Year}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Section}\')">{Section}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Grade Level}\')">{Grade Level}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Rank}\')">{Rank}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{GWA}\')">{GWA}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Adviser}\')">{Adviser}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Principal}\')">{Principal}</button>
                        <button type="button" class="btn btn-primary" onclick="insertText(\'{Date}\')">{Date}</button>
                    </div> 
                </div>
            </div>
        </div>', 
    'myTemplate', 
    'Update', 
    0);
}

//goodmoral / COE
function gooodmDocumentPreview() {
    
    return '
        <div class="document-preview container-preview" id = "doc-container">
            <header class="header-gmoral">
                <div class="img">
                    <img src="../../../assets/images/kagawaran.png" alt="">
                </div>
                <h5>Republic of the Philippines</h5>
                <h4>Department of Education</h4>
                <h6>Region III – Central Luzon</h6>
                <h6>Schools Division of the City of Baliwag</h6>
                <h6><b>BALIWAG SOUTH CENTRAL SCHOOL</b></h6>
                <h6>J. Buizon St., Sto. Cristo, City of Baliwag, Bulacan</h6>
            </header>
            <div id="preview">
                <div id="certificate-message" style="font-size: 14px">' . genedocuments() . '</div>
            </div>
            <footer class="footer-gmoral">
                <div class="img-deped">
                    <img src="../../../assets/images/matatag.png" alt="">
                </div>
                <div class="school-logo">
                    <img src="../../../assets/images/BSCS-logo.png" alt="">
                </div>
                <div class="location">
                    <h6><b>BALIWAG SOUTH CENTRAL SCHOOL</b></h6>
                    <h6>J. Buizon St., Sto. Cristo, City of Baliwag, Bulacan</h6>
                    <h6>E-mail Address: 104750@deped.gov.ph</h6>
                </div>
            </footer>
        </div>
    ';
}


//sf10
function sfDocumentPreview() {
    global $data;

    return display_all(
        $data['registrar_generate']['sf_information'],
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
   
    $data['registrar_generate']['sf_schoolyear']['value'] = [$id];
    return display_all(
        $data['registrar_generate']['sf_schoolyear'],
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
    $data['registrar_generate']['sf_gradeLevel']['value'] = [$id];
    display_all(
        $data['registrar_generate']['sf_gradeLevel'],
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


function request($doc) {
    $docu = '';
    if ($doc !== "3") {
        $docu = '<div class="search-container mb-3">
                    <label for="purpose" class="form-label">Reason for document request: </label>
                    <input type="text" class="form-control" name="documentPurpose" placeholder="Enter the purpose of document request." required>
                </div>';
    } else {
        $docu = '<div class="search-container mb-3">
                    <label for="schoolName" class="form-label">Requested by: </label>
                    <input type="text" class="form-control" name="documentPurpose" placeholder="Enter the name of the school who requested the form." required>
                </div>';
    }

    return modal(
        'Document Release',
        '',
        '<div class="modal-body">' . $docu . '</div>', 
        'printModal',
        'Print',
        0
    );
}



function generatePdfScript() {
    if (isset($_SESSION['Document']) && $_SESSION['Document'] != '' && isset($_POST['btnPrint'])) {
        $documentData = json_encode($_SESSION['Document']);
        unset($_SESSION['Document']);
        echo '<script>
            generatePDF(' . $documentData . ').then(function() {
                console.log("PDF generated successfully");
            }).catch(function(error) {
                console.error("Error generating PDF: ", error);
            });
        </script>';
    }
}


//tracker
function trackerdoc() {
    global $data;

    $output = display_all(
        $data['registrar_tracker']['display'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                "<tr>
                    <td>" . htmlspecialchars($row['do_timestamp']) . "</td>
                    <td>" . htmlspecialchars($row['st_lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['us_gender']) . "</td>
                    <td>" . htmlspecialchars($row['fo_name']) . "</td>
                    <td class='status'>" . htmlspecialchars($row['do_remarks']) . "</td>
                </tr>"
            );
        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='7'>No record found</td></tr>";
    }

    return $output;
}


//registrarcalendar
function calendarapprove() {
    global $data;

    $approveData = []; 
    display_all(
        $data['registrarcalendar']['approve'],
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
							
							<div class="time-inputs">
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
        $data['registrarcalendar']['upcoming'],
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
                           <span class = "label"> Requested By:</span> ' . htmlspecialchars($row['name']) . '
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
        $data['registrarcalendar']['rejected'],
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
        $data['registrarcalendar']['pending'],
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





















//registrar_
function das() {
    global $data;


    $output = display_all(
        $data['registrar_viewstudlist']['display'],
        null,
        $output = function ($row = null, $id = null) {
            return (
                ""
            );
        }
    );

    if ($output == '') {
        $output = "<tr><td colspan='7'>No record found</td></tr>";
    }

    return $output;
}

?>