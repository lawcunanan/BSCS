<?php 
require 'filesvendor/vendor/autoload.php';
require 'excelpush.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


function import($type, $handled, $header) {
    $fileName = $_FILES["upload_excel"]["tmp_name"];
    if ($_FILES["upload_excel"]["error"] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error uploading file.');</script>";
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($fileName);
        $sheetCount = $spreadsheet->getSheetCount();
        $sheetData = [];
        $limitIndex = '';

        for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
            $sheet = $spreadsheet->getSheet($sheetIndex);
            $rows = $sheet->toArray(null, true, true, true);
            
            foreach ($rows as $rowIndex => $row) {
                foreach ($row as $subIndex => $subValue) {
                    if (preg_grep("/". $handled[0] . "|" . $handled[1] . "|" . $handled[2] . "|" . "/i", [$subValue])) {
                        $index = excelColumnToNumberOrLetter($subIndex) + 1;

                        for ($getIndex = $index; $getIndex < count($rows); $getIndex++) {
                            $colIndex = excelColumnToNumberOrLetter($getIndex);

                            if (isset($rows[$rowIndex][$colIndex]) && $rows[$rowIndex][$colIndex] != null && !preg_grep("/" . $handled[0] . "|" . $handled[1] . "|" . $handled[2] . "/i", [$rows[$rowIndex][$colIndex]])) {
                                $inputArray = [
                                    splitDynamic($subValue),
                                    splitDynamic($rows[$rowIndex][$colIndex])
                                ];

                                foreach (splitDynamic($inputArray) as $key => $value) {
                                    if ($rows[$rowIndex][$colIndex] != '' && $rows[$rowIndex][$colIndex] != null) {
                                        if (preg_grep("/School Year|Academic Year/i", [trim($key)]) && !isset($sheetData["Handled"]['school year'])) {
                                            $sheetData["Handled"]['school year'] = trim($value);
                                        }

                                        if (preg_grep("/Section/i", [trim($key)]) && !isset($sheetData["Handled"]['section'])) {
                                            $sheetData["Handled"]['section'] = trim($value);
                                        }

                                        if (preg_grep("/Grade|Level/i", [trim($key)]) && !isset($sheetData["Handled"]['grade level'])) {
                                            $sheetData["Handled"]['grade level'] = trim($value);
                                        }

                                    }
                                }

                                break;
                            }

                            if(isset($rows[$rowIndex][$colIndex]) && (preg_grep("/" . $handled[0] . "|" . $handled[1] . "|" . $handled[2] . "/i", [$rows[$rowIndex][$colIndex]]))){
                                break;
                            }
                        }
                    }
                }
            }

            foreach ($rows as $rowIndex => $row) {
                foreach ($row as $subIndex => $subValue) {
                    if ($type == 'studentGrade' && in_array(strtolower($subValue), array_map('strtolower', $header))) {
                        $find = false;
                        $ctr = 1; $quarter = ['1' => 1, '2' => 2, '3' => 3, '4' => 4];

                        for ($quarterIndex = $rowIndex; $quarterIndex < count($rows); $quarterIndex++) {
                            if ($find) break;

                            foreach ($rows[$quarterIndex] as $listIndex => $listValue) {
                                if ($ctr > 4) break;

                                $listValue = preg_replace('/\D/', '', $listValue);
                                if (preg_grep("/1|2|3|4/i", [$listValue]) && excelColumnToNumberOrLetter($listIndex) >= excelColumnToNumberOrLetter($subIndex) && in_array($listValue, $quarter)) {
                                    for ($gradeIndex = $quarterIndex + 1; $gradeIndex < count($rows); $gradeIndex++) {
                                        if ($rows[$gradeIndex][$listIndex] !== '' && $rows[$gradeIndex][$listIndex] !== null && is_numeric($rows[$gradeIndex][$listIndex])) {
                                            foreach ($rows[$gradeIndex] as $nameValue) {
                                                if ((isset($handled[3]) && $nameValue == $handled[3]) || (!isset($handled[3]) && $nameValue !== '' && $nameValue !== null && !is_numeric($nameValue))) {
                                                    if (!isset($sheetData["Data"][$nameValue])) {
                                                        $sheetData["Data"][$nameValue] = ['Name' => $nameValue];
                                                    }

                                                    if (!isset($sheetData["Data"][$nameValue][$subValue])) {
                                                        $sheetData["Data"][$nameValue][$subValue] = [
                                                            "1" => 0,
                                                            "2" => 0,
                                                            "3" => 0,
                                                            "4" => 0
                                                        ];
                                                    }

                                                    if ($rows[$gradeIndex][$listIndex] !== 0 && $rows[$gradeIndex][$listIndex] !== null) {
                                                        $sheetData["Data"][$nameValue][$subValue][$listValue] = $rows[$gradeIndex][$listIndex];
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }

                                    $ctr++;
                                    $find = true;
                                    //array_diff($quarter, [$listValue]);
                                    $quarter[$listValue] = null;
                                    
                                }
                            }
                        }
                    } else if ($type == 'studentInfo' && preg_grep("/" . $header . "/i", [$subValue])) {
                       
                        if ($subValue == 'LRN') $limitIndex = $subIndex;
                        for ($valueIndex = $rowIndex + 1; $valueIndex < count($rows); $valueIndex++) {
                            if (!empty($rows[$valueIndex][$subIndex]) && isset($rows[$valueIndex][$limitIndex])) {
                                $sheetData["Data"][$rows[$valueIndex][$limitIndex]][getValue($subValue)] = $rows[$valueIndex][$subIndex];
                            }
                        }
                    }
                }
            }
        }

        //echo '<pre>' . json_encode($sheetData, JSON_PRETTY_PRINT) . '</pre>';
        return $sheetData;
    } catch (Exception $e) {
        echo "<script>alert('Error processing file: " . $e->getMessage() . "');</script>";
    }
}

function getValue($subValue) {
    $cleanedValue = preg_replace('/[^a-z0-9\s]/', '', strtolower($subValue));

    if (preg_match("/purok|house|street|sitio/i", $cleanedValue)) {
        return 'purok';
    } elseif (preg_match("/municipal|city/i", $cleanedValue)) {
        return 'municipal';
    } elseif (preg_match("/tongue/i", $cleanedValue)) {
        return 'tongue';
    } elseif (preg_match("/father/i", $cleanedValue)) {
        return 'father name';
    } elseif (preg_match("/Mother's|Maiden/i", $cleanedValue)) {
        return 'mother name';
    }elseif (preg_match("/Contact/i", $cleanedValue)) {
        return 'contact';
    }  elseif (preg_match("/guardian/i", $cleanedValue)) {
        return 'guardian name';
    } else {
        return strtok($cleanedValue, ' ');
    }
}

function excelColumnToNumberOrLetter($input) {
    if (is_string($input)) {
        $length = strlen($input);
        $number = 0;

        for ($i = 0; $i < $length; $i++) {
            $number = $number * 26 + (ord(strtoupper($input[$i])) - ord('A') + 1);
        }

        return $number;
    }

    if (is_int($input)) {
        $letter = '';
        while ($input > 0) {
            $input--;
            $letter = chr($input % 26 + 65) . $letter;
            $input = floor($input / 26);
        }

        return $letter;
    }

    return null;
}

function splitDynamic($input) {
    if (is_array($input)) {
        $values = [];
        if (count($input[0]) == count($input[1])) {
            foreach ($input[0] as $index => $header) {
                $values[$header] = $input[1][$index];
            }
        }
        return $values;
    } else {
        $pattern = '/[,&|]/';
        $results = preg_split($pattern, $input);
        $filteredResults = array_filter($results, fn($value) => trim($value) !== '');

        return array_values($filteredResults);
    }

    return false;
}

function name($studentName) {
    $inputArray = [
        splitDynamic("Last,First,Middle"),
        splitDynamic($studentName)
    ];

    $inputArray = splitDynamic($inputArray);

    return [
        isset($inputArray['Last']) ? trim($inputArray['Last']) : '',
        isset($inputArray['First']) ? trim($inputArray['First']) : '',
        isset($inputArray['Middle']) ? trim($inputArray['Middle']) : ''
    ];
}

?>
