<?php 
require "../../../model/function.php";

//admin
function currentNumber() {
    global $data;
    $ctr = 0;

    $output = display_all(
        $data['admin']['display'],
        null,
        function ($row = null, $id = null) use (&$schoolyear, &$ctr) {
           
            $ctr += $row['number'];
            return (
                '<tr>
					<td>' . htmlspecialchars($row['us_type']) . '</td>
					<td>' . htmlspecialchars($row['number']) . '</td>
				</tr>'
            );
        }
    );
    
    if($ctr > 0){
       $output .= "<tr><td><b>OVERALL</b></td><td><b>{$ctr}</b></td></tr>";
    }
    return $output;
}

function username() {
    global $data;
    return display_all(
        $data['admin']['name'],
        null,
        function ($row = null, $id = null) {
          
            if ($row && isset($row['name'])) {
                return "<b>{$row['name']}!</b>"; 
            }
            return ""; 
        }
    );
}




function addheader() {
    return modal(
        'Add New Excel Header',
        '',
        '<div class="modal-body" style="display: flex; flex-wrap: wrap; gap: 15px; width: 100%;">
            <div style="flex: 1;">
                <label for="eventType" class="form-label">Excel Header Type</label>
                <select class="form-select" id="headerType" required name="headerType">
                    <option value="info">SF 1</option>
                    <option value="subject">SF 10</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label for="excelHeader" class="form-label">Excel Header Name</label>
                <input type="text" class="form-control" id="excelHeader" name ="headerName" placeholder="Name" required>
            </div>
        </div>',
        'addHeader',
        'Add',
        0
    );
}




function headerlist() {
    global $data;

    return display_all(
        $data['excelheader']['display'],
        null,
        $output = function ($row = null, $id = null) {
            return '  
                <tr>
                    <td>' . htmlspecialchars($row['type']) . '</td>
                    <td>' . htmlspecialchars($row['sf_name']) . '</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateHeader' . htmlspecialchars($row['sf_id']) . '">Update</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#myDelete" onclick="setID(' . htmlspecialchars($row['sf_id']) . ', \'btnDelete_Header\');">Delete</button>
                    </td>
                </tr>' .
                modal(
                    'Update Excel Header',
                    '',
                    '<div class="modal-body" style="display: flex; flex-wrap: wrap; gap: 15px; width: 100%;">
                        <div style="flex: 1;">
                            <label for="headerType" class="form-label">Excel Header Type</label>
                            <select class="form-select" id="headerType" required name="headerType">
                                <option value="info">SF 1</option>
                                <option value="subject">SF 10</option>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label for="excelHeader" class="form-label">Excel Header Name</label>
                            <input type="text" class="form-control" id="excelHeader" name="headerName" placeholder="Name" required value="' . htmlspecialchars($row['sf_name']) . '">
                        </div>
                    </div>',
                    'updateHeader' . htmlspecialchars($row['sf_id']),
                    'Update',
                    htmlspecialchars($row['sf_id'])
                );
        }
    );
}


function exceldelete() {
    return modal(
        "Delete Header Excel", 
        "",  
        "<div class='modal-body body1'>
            <h6>
                Are you sure you want to delete this header?
            </h6>
        </div>", 
        "myDelete", 
        "Delete_Header",
        0
    );
}



//admincalendar
function calendarapprove() {
    global $data;

    $approveData = []; 
    display_all(
        $data['admincalendar']['approve'],
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
                                    <option value="Division-wide">Division-wide</option>
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
    global $admin;
    
    return display_all(
        $data['admincalendar']['upcoming'],
        $admin,
        $output = function ($row = null, $id = null) {
           
        
            $eventCard = '
                <div class="event-card ' . htmlspecialchars($row['ev_type']) . '">
                    <div class="event-type-badge ' . htmlspecialchars($row['ev_type']) . '">
                        ' . htmlspecialchars($row['ev_type']) . ' Event
                    </div>
                    <div class="event-title"><span class="label">Title:</span> ' . htmlspecialchars($row['ev_title']) . '</div>
                    <div class="event-datetime">
                        <div class="event-date">
                            <span class="label">Date:</span> ' . htmlspecialchars($row['date']) . '
                        </div>
                        <div class="event-time">
                            <span class="label">Time:</span> ' . htmlspecialchars($row['time']) . '
                        </div>
                    </div>
                    <hr>' . 
                    (($row['ev_usID'] == $id) ? '
                        <div class="button-container">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventDetails_' . htmlspecialchars($row['ev_id']) . '">
                                Show Details
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#myDelete" onclick="setID(\'' . htmlspecialchars($row['ev_id']) . '\', \'btnDelete\')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>' : 
                        '<div class="event-time">
                            <span class = "label">Requested By:</span> ' . htmlspecialchars($row['name']) . '
                        </div>'
                    ) . 
                '</div>';

            $modal = ($row['ev_usID'] == $id) ? modal(
                'Event Details',
                '',
                '<div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" class="form-control" id="viewEventTitle" required value="' . htmlspecialchars($row['ev_title']) . '" name="ev_title" ' . ($row['time'] == 'NA' ? 'readonly' : '') . ' />
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="viewEventStart" required min="' . date('Y-m-d') . '" value="' . htmlspecialchars($row['ev_sdate']) . '" name="ev_sdate" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="viewEventEnd" min="' . date('Y-m-d') . '" value="' . htmlspecialchars($row['ev_edate']) . '" name="ev_edate" />
                        </div>
                    </div>' . 
                    ($row['time'] !== 'NA' ? '
                    <div style="display: flex; gap: 10px;">
                        <div class="mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="viewEventStartTime" value="' . htmlspecialchars($row['ev_stime']) . '" name="ev_stime" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" class="form-control" id="viewEventEndTime" value="' . htmlspecialchars($row['ev_etime']) . '" name="ev_etime" required />
                        </div>
                    </div>' : '') . '
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="viewEventDescription" rows="3" required name="ev_description">' . htmlspecialchars($row['ev_description']) . '</textarea>
                    </div>
                </div>',
                'eventDetails_' . htmlspecialchars($row['ev_id']),
                'Save',
                htmlspecialchars($row['ev_id'])
            ) : '';

            return $eventCard . $modal;
        }
    );
}



function calendarrejected() {
    global $data;

    return display_all(
        $data['admincalendar']['rejected'],
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




function createacc() {
    return modal(
        "Create New Account", 
        "",  
        '<div class="modal-body">
                        <input type="hidden" id="userId">
                        
                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-user"></i>
                                <h6 class="section-title">Personal Information</h6>
                            </div>
                            <div class="form-row form-row-3">
                                <div>
                                    <label class="form-label" for="fname">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter first name" required>
                                </div>
                                <div>
                                    <label class="form-label" for="mname">Middle Name</label>
                                    <input type="text" class="form-control" id="mname" name="mname" placeholder="Enter middle name" required>
                                </div>
                                <div>
                                    <label class="form-label" for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter last name" required>
                                </div>
                            </div>
    
                            <div class="form-row form-row-3">
                                <div>
                                    <label class="form-label" for="sex">Sex</label>
                                    <select class="form-control" id="sex" name="sex" required>
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" for="bdate">Birth Date</label>
                                    <input type="date" class="form-control" id="bdate" name="bdate" required max = "'.date('Y-m-d', strtotime('-18 years')).'">
                                </div>
                            </div>
                        </div>
    
                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-address-card"></i>
                                <h6 class="section-title">Contact Information</h6>
                            </div>
                            <div class="form-row">
                                <div>
                                    <label class="form-label" for="contact">Contact Number</label>
                                   <input type="tel" class="form-control" id="contact" name="contact" placeholder="Enter contact number" required maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits">

                                </div>
                            </div>
                        </div>
    
                        <!-- Address Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-map-marker-alt"></i>
                                <h6 class="section-title">Address Information</h6>
                            </div>
                            <div class="form-row form-row-3">
                                <div>
                                    <label class="form-label" for="province">Province/City</label>
                                    <select class="form-control" id="province" name="province" required  onchange="setCity(setCode(\'province\'), \'municipality\')">
                                        <option value="" disabled selected hidden>Select</option>
                                    </select>
                                </div>
                                <div>
                                   <label class="form-label" for="municipality">Municipality</label>
                                   <select class="form-control" id="municipality" name="municipality" required onchange="setBarangays(setCode(\'municipality\'), \'barangay\')">
                                        <option value="" disabled selected hidden>Select</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" for="barangay">Barangay</label>
                                    <select class="form-control" id="barangay" name="barangay" required>
                                        <option value="" disabled selected hidden>Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div>
                                    <label class="form-label" for="street">Street Address</label>
                                    <input type="text" class="form-control" id="street" name="street" placeholder="Enter street address" required>
                                </div>
                            </div>
                        </div>
    
                        <!-- Profile Image Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-camera"></i>
                                <h6 class="section-title">Profile Image</h6>
                            </div>
                            <div class="image-upload">
                                <img id="preview_image" src="https://placehold.co/150x150" alt="Profile Preview" class="preview-image">
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control" id="profile_image" name="picture" accept="image/*" required onchange="setimg(\'preview_image\', \'profile_image\')">
                                    <small class="form-text text-muted">Upload a square image for best results. Maximum file size: 50MB</small>
                                </div>
                            </div>
                        </div>

                          <!-- Account Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-lock"></i>
                                <h6 class="section-title">Account Information</h6>
                            </div>
                            <div class="form-row form-row-3">
                                <div>
                                    <label class="form-label" for="role">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select a role</option>
                                        <option value="admin">IT Head</option>
                                        <option value="principal">Principal</option>
                                        <option value="secretary">Principal\'s Secretary</option>
                                        <option value="registrar">Registrar</option>
                                        <option value="teacher">Teacher</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter valid email" required>
                                </div>
                                <div>
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" 
                                        pattern="^^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$" 
                                        title="At least 8 characters long. Must include one uppercase letter, one lowercase letter, one number, and one special character." 
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>', 
        "accountModal", 
        "Create",
        0
    );
}


function manageacc() {
    global $data;

    return display_all(
        $data['manageacc']['display'],
        null,
        $output = function ($row = null, $id = null) {
            $modalId = 'AccountInfo' . htmlspecialchars($row['us_id']);
            $modalId1 = 'PersonalInfo' . htmlspecialchars($row['us_id']);
            $modalId2 = 'DeleteAccount' . htmlspecialchars($row['us_id']);
            $list = (
                '<tr>
                    <td>' . htmlspecialchars($row['us_id']) . '</td>
                    <td>' . htmlspecialchars($row['name']) . '</td>
                    <td>' . htmlspecialchars($row['us_email']) . '</td>
                    <td>' . ucfirst(strtolower(htmlspecialchars($row['us_type']))) . '</td>
                    <td>
                        <select name="action" id="action" class="form-select">
                            <option value="" disabled hidden selected>Select an action</option>
                            <option onclick="openModal(this, \'' . $modalId . '\') ">Edit Account Information</option>
                            <option onclick="openModal(this, \'' . $modalId1 . '\'); setProvince(\'province'.htmlspecialchars($row['us_id']).'\')">Edit Personal Information</option>
                            <option onclick="openModal(this, \'' . $modalId2 . '\')">Delete</option>
                        </select>
                    </td>
                </tr>' 
            );

            $view = modal(
                "Edit Account Information", 
                "",  
                '<div class="modal-body">
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-lock"></i>
                            <h6 class="section-title">Account Information</h6>
                        </div>
                        <div class="form-row form-row-3">
                            <div>
                                <label class="form-label" for="role">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="' . htmlspecialchars($row['us_type']).'">' . ucfirst(strtolower(htmlspecialchars($row['us_type']))) .'</option>
                                    <option value="admin">IT Head</option>
                                    <option value="principal">Principal</option>
                                    <option value="secretary">Principal\'s Secretary</option>
                                    <option value="registrar">Registrar</option>
                                    <option value="teacher">Teacher</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter valid email" required value = "' . htmlspecialchars($row['us_email']).'">
                            </div>
                            <div>
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
                                    pattern="^^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$" 
                                    title="At least 8 characters long. Must include one uppercase letter, one lowercase letter, one number, and one special character." 
                                >
                            </div>
                        </div>
                    </div>
                </div>', 
                $modalId, 
                "Apply_Changes",
                htmlspecialchars($row['us_id'])
            );

            $personal = modal(
                "Edit Personal Information", 
                "",  
                '<div class="modal-body">
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-user"></i>
                            <h6 class="section-title">Personal Information</h6>
                        </div>
                        <div class="form-row form-row-3">
                            <div>
                                <label class="form-label" for="fname">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter first name" required value = "' . htmlspecialchars($row['us_fname']).'">
                            </div>
                            <div>
                                <label class="form-label" for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" placeholder="Enter middle name" required value = "' . htmlspecialchars($row['us_mname']).'">
                            </div>
                            <div>
                                <label class="form-label" for="lname">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter last name" required value = "' . htmlspecialchars($row['us_lname']).'">
                            </div>
                        </div>
                        <div class="form-row form-row-3">
    
                            <div>
                                <label class="form-label" for="sex">Sex</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="' . htmlspecialchars($row['us_gender']).'">' . htmlspecialchars($row['us_gender']).'</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" for="bdate">Birth Date</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" required value = "' . htmlspecialchars($row['us_birthday']).'" max = "'.date('Y-m-d', strtotime('-18 years')).'">
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-address-card"></i>
                            <h6 class="section-title">Contact Information</h6>
                        </div>
                        <div class="form-row">
                            <div>
                                <label class="form-label" for="contact">Contact Number</label>
                                <input type="tel" class="form-control" id="contact" name="contact" placeholder="Enter contact number" required value = "' . htmlspecialchars($row['us_contact']).'" maxlength="11" pattern="\d{11}" title="Please enter exactly 11 digits">
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h6 class="section-title">Address Information</h6>
                        </div>
                        <div class="form-row form-row-3">
                            <div>
                                 <label class="form-label" for="province">Province/City</label>
                                    <select class="form-control" id="province'.htmlspecialchars($row['us_id']).'" name="province" required  onchange="setCity(setCode(\'province'.htmlspecialchars($row['us_id']).'\'), \'municipality'.htmlspecialchars($row['us_id']).'\')">
                                    <option value="'.htmlspecialchars($row['us_province']).'" >'.htmlspecialchars(preg_replace('/[^a-zA-Z]/', '', $row['us_province'])).'</option>
                                    </select>
                                </div>
                                <div>
                                   <label class="form-label" for="municipality">Municipality</label>
                                   <select class="form-control" id="municipality'.htmlspecialchars($row['us_id']).'" name="municipality" required onchange="setBarangays(setCode(\'municipality'.htmlspecialchars($row['us_id']).'\'), \'barangay'.htmlspecialchars($row['us_id']).'\')">
                                        <option value="'.htmlspecialchars($row['us_municipality']).'" >'.htmlspecialchars(preg_replace('/[^a-zA-Z]/', '', $row['us_municipality'])).'</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" for="barangay">Barangay</label>
                                    <select class="form-control" id="barangay'.htmlspecialchars($row['us_id']).'" name="barangay" required>
                                    <option value="'.htmlspecialchars($row['us_barangay']).'" >'.htmlspecialchars(preg_replace('/[^a-zA-Z]/', '', $row['us_barangay'])).'</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div>
                                <label class="form-label" for="street">Street Address</label>
                                <input type="text" class="form-control" id="street" name="street" placeholder="Enter street address" required value = "'.htmlspecialchars($row['us_street']).'">
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-camera"></i>
                            <h6 class="section-title">Profile Image</h6>
                        </div>
                        <div class="image-upload">
                            <img id="preview_image'.htmlspecialchars($row['us_id']).'" src="../../../model/picture/User_'.htmlspecialchars($row['us_id']).'.png" alt="Profile Preview" class="preview-image">
                            <div class="flex-grow-1">
                                <input type="file" class="form-control" id="profile_image'.htmlspecialchars($row['us_id']).'" name="picture" accept="image/*" onchange="setimg(\'preview_image'.htmlspecialchars($row['us_id']).'\', \'profile_image'.htmlspecialchars($row['us_id']).'\')" >
                                <small class="form-text text-muted">Upload a square image for best results. Maximum file size: 50MB</small>
                            </div>
                        </div>
                    </div> 
                </div>', 
                $modalId1, 
                "Save_Changes",
                htmlspecialchars($row['us_id'])
            );

            $delete = modal(
                "Delete Account", 
                "",  
                "<div class='modal-body body1'>
                    <h6>
                        Are you sure you want to delete this account?
                    </h6>
                </div>", 
                $modalId2, 
                "Delete_Account",
                htmlspecialchars($row['us_id'])
            );

            return ($list . $view . $personal . $delete);
        }
    );
}


function schoolinformation() {
    global $data;

    return display_all(
        $data['schoolinfo']['display'],
        null,
        function ($row = null, $id = null) {
            return (
                '<div class="form-row form-row-2">
                    <div>
                        <label class="form-label" for="sID">School ID</label>
                        <input type="text" class="form-control" id="sID" name="sID" 
                            placeholder="Enter the school ID" value="'.htmlspecialchars($row['si_schoolID']).'" required disabled>
                    </div>
                    <div>
                        <label class="form-label" for="sname">School Name</label>
                        <input type="text" class="form-control" id="sname" name="sname" 
                            placeholder="Enter the school\'s name" value="'.htmlspecialchars($row['si_name']).'" required disabled>
                    </div>
                </div>

                <div class="form-row form-row-3">
                    <div>
                        <label class="form-label" for="region">Region</label>
                        <select class="form-select" id="region" name="region" required disabled>
                            <option value="'.htmlspecialchars($row['si_region']).'">'.htmlspecialchars($row['si_region']).'</option>
                            <option value="Region I">Region I</option>
                            <option value="Region II">Region II</option>
                            <option value="Region III">Region III</option>
                            <option value="Region IV-A">Region IV-A</option>
                            <option value="Region IV-B">Region IV-B</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="division">Division</label>
                        <select type="text" class="form-select" id="division" name="division" required disabled>
                            <option value="'.htmlspecialchars($row['si_division']).'">'.htmlspecialchars($row['si_division']).'</option>
                            <option value="Aurora">Aurora</option>
                            <option value="Bataa">Bataan</option>
                            <option value="Baliwag Cit">Baliwag City</option>
                            <option value="Bulacan">Bulacan</option>
                            <option value="Nueva Ecija">Nueva Ecija</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="district">District</label>
                        <input type="text" class="form-control" id="district" name="district" 
                            value="'.htmlspecialchars($row['si_district']).'" required disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label class="form-label" for="schoolLogo">School Logo</label>
                        <input type="file" class="form-control" id="schoolLogo" name="picture" 
                            accept="image/*" required disabled >
                        <div class="logo-preview-container">
                            <img id="logoPreview" class="logo-preview" src="../../../model/picture/Logo_1.png" alt="School Logo Preview">
                        </div>
                    </div>
                </div>'
            );
        }
    );
}





?>