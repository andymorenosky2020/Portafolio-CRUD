<?php include("cabecera.php"); ?>
<br/>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Datos Proyecto</div>
                <div class="card-body">
                    <form action="conexion.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombre">Nombre del Proyecto:</label>
                            <input class="form-control" type="text" name="nombre" id="nombre" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="imagen">Imagen del Proyecto:</label>
                            <input class="form-control" type="file" name="imagen" id="imagen" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="descripcion">Descripción del Proyecto:</label>
                            <input class="form-control" type="text" name="descripcion" id="descripcion" required>
                        </div>
                        <br>
                        <input class="btn btn-success" type="submit" value="Enviar Proyecto">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-primary">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Conectar a la base de datos y obtener los datos
                        $serverName = "DESKTOP-J5P6950";
                        $database = "Portafolio";
                        $connectionInfo = array(
                            "Database" => $database,
                            "CharacterSet" => "UTF-8"
                        );
                        $conn = sqlsrv_connect($serverName, $connectionInfo);

                        if ($conn) {
                            // Manejo de la eliminación del proyecto
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_proyecto'])) {
                                $idEliminar = filter_input(INPUT_POST, 'eliminar_proyecto', FILTER_SANITIZE_NUMBER_INT);

                                if ($idEliminar) {
                                    $deleteSQL = "DELETE FROM proyecto WHERE id = ?";
                                    $deleteParams = array($idEliminar);

                                    $deleteStmt = sqlsrv_prepare($conn, $deleteSQL, $deleteParams);

                                    if ($deleteStmt) {
                                        if (sqlsrv_execute($deleteStmt)) {
                                            echo "Proyecto eliminado correctamente.";
                                        } else {
                                            echo "Error al eliminar el proyecto: " . print_r(sqlsrv_errors(), true);
                                        }
                                    } else {
                                        echo "Error al preparar la consulta de eliminación: " . print_r(sqlsrv_errors(), true);
                                    }
                                }
                            }

                            $sql = "SELECT id, nombre, imagen, descripcion FROM proyecto";
                            $stmt = sqlsrv_query($conn, $sql);

                            if ($stmt) {
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                    echo "<td><img src='uploads/" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['nombre']) . "' style='width: 100px;'></td>";
                                    echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                                    echo "<td>";
                                    echo "<form method='post'>";
                                    echo "<input type='hidden' name='eliminar_proyecto' value='" . htmlspecialchars($row['id']) . "'>";
                                    echo "<button type='submit' class='btn btn-danger'>Eliminar</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "Error en la consulta.";
                            }

                            sqlsrv_close($conn);
                        } else {
                            echo "Error de conexión.";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("pie.php"); ?>

