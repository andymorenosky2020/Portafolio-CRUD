<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serverName = "DESKTOP-J5P6950";
    $database = "Portafolio";

    $connectionInfo = array(
        "Database" => $database,
        "CharacterSet" => "UTF-8"
    );

    $conn = sqlsrv_connect($serverName, $connectionInfo);

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $Nom = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $Img = $_FILES['imagen']['name']; // Obtener el nombre del archivo de la imagen
    $Des = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

    if ($Nom && $Img && $Des) {
        // Mover el archivo subido a la carpeta "img"
        $imagen_temporal = $_FILES['imagen']['tmp_name'];
        $imagen = $_FILES['imagen']['name'];
        move_uploaded_file($imagen_temporal, "img/" . $imagen);

        $insertar = "INSERT INTO proyecto (nombre, imagen, descripcion) VALUES (?, ?, ?)";
        $params = array($Nom, $imagen, $Des);

        $stmt = sqlsrv_prepare($conn, $insertar, $params);

        if ($stmt) {
            if (sqlsrv_execute($stmt)) {
                echo "Proyecto Agregado Correctamente.";
            } else {
                echo "Proyecto No Agregado.";
                die(print_r(sqlsrv_errors(), true));
            }
        } else {
            echo "Error al preparar la consulta.";
            die(print_r(sqlsrv_errors(), true));
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_proyecto'])) {
        $idEliminar = filter_input(INPUT_POST, 'eliminar_proyecto', FILTER_SANITIZE_NUMBER_INT);

        if ($idEliminar) {
            $deleteSQL = "DELETE FROM proyecto WHERE id = ?";
            $deleteParams = array($idEliminar);

            $deleteStmt = sqlsrv_prepare($conn, $deleteSQL, $deleteParams);

            if ($deleteStmt) {
                if (sqlsrv_execute($deleteStmt)) {
                    echo "Proyecto eliminado correctamente.";

                    // Obtener el nombre de la imagen asociada al proyecto y eliminarla del directorio
                    $imagen = $objConexion->consultar("SELECT imagen FROM proyecto WHERE id = " . $idEliminar);
                    if ($imagen) {
                        $rutaImagen = "img/" . $imagen[0]['imagen'];
                        if (file_exists($rutaImagen)) {
                            unlink($rutaImagen);
                            echo "Imagen asociada al proyecto eliminada correctamente.";
                        } else {
                            echo "La imagen asociada al proyecto no existe.";
                        }
                    } else {
                        echo "No se pudo obtener la información de la imagen asociada al proyecto.";
                    }
                } else {
                    echo "Error al eliminar el proyecto: " . print_r(sqlsrv_errors(), true);
                }
            } else {
                echo "Error al preparar la consulta de eliminación: " . print_r(sqlsrv_errors(), true);
            }
        }
    } else {
        echo "Datos de entrada no válidos.";
    }

    sqlsrv_close($conn);
} else {
    echo "Método de solicitud no válido.";
}
?>


