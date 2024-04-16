<?php 

function checkColumn($column = '') {
	if (form_error($column) != '') {
		return 'is-invalid';
	}
	else if (set_value($column) != '' && form_error($column) == '') {
		return 'is-valid';
	}
	else {
		return '';
	}

}

function getNextExcelColumn($currentColumn, $increment = 1) {
    $columnIndex = 0;
    $length = strlen($currentColumn);
    
    // Convert current column letters to numeric index
    for ($i = 0; $i < $length; $i++) {
        $columnIndex = $columnIndex * 26 + (ord($currentColumn[$i]) - ord('A') + 1);
    }
    
    // Increment the index
    $columnIndex += $increment;
    
    // Convert numeric index back to column letters
    $result = '';
    while ($columnIndex > 0) {
        $remainder = ($columnIndex - 1) % 26;
        $result = chr(ord('A') + $remainder) . $result;
        $columnIndex = intval(($columnIndex - $remainder) / 26);
    }
    
    return $result;
}
