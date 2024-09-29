<?php

set_time_limit(300); 

include("config.php");


if (isset($_FILES['sqlFile'])) {
    $file = $_FILES['sqlFile']['tmp_name'];

    if (file_exists($file)) {
        $sql = file_get_contents($file);

       
        if ($conn->multi_query($sql)) {
            do {
               
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result()); 
            echo "Importación realizada con éxito.";
        } else {
            echo "Error en la importación: " . $conn->error;
        }
    }
} else {
    echo "No se ha subido ningún archivo.";
}

$conn->close();
?>