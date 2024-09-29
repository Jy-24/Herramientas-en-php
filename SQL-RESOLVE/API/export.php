<?php

include("config.php");

$tables = array();
$result = $conn->query("SHOW TABLES");
while($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

$sql_content = '';
foreach($tables as $table){
    $result = $conn->query("SHOW CREATE TABLE $table");
    $table_create = $result->fetch_row()[1];
    $sql_content .= "-- Estructura de la tabla $table\n". $table_create . ";\n\n";

   
    $result = $conn->query("SELECT * FROM $table");
    while ($row = $result->fetch_assoc()) {
        $sql_content .= "INSERT INTO $table VALUES (" . implode(',', array_map(function($value) {
            return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
        }, $row)) . ");\n";
    }
    $sql_content .= "\n";
}

$file_name = 'export_' . $dbname . '_' . date('Y-m-d_H-i-s') . '.sql';
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
echo $sql_content;
$conn->close();
?>