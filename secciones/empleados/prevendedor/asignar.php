<?php
include("../../../bd.php");
include("../../../templates/header.php");
$sentencia_ruta = $conexion->prepare("SELECT * FROM `rutas`");
$sentencia_ruta->execute();
$rutas = $sentencia_ruta->fetchAll(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("SELECT e.*, r.rol AS nombre_rol FROM `empleados` e INNER JOIN `rol` r ON e.rol = r.idrol WHERE r.idrol = 3 and e.activo = 1");
$sentencia->execute();
$empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado el formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y realizar la inserción
    $id_empleado = $_POST['Empleado'];
    $id_ruta = $_POST['Ruta'];
    $fecha_asignacion = $_POST['Fecha_de_Asignacion'];
    $fecha_visita = $_POST['Fecha_de_Visita'];

    // Preparar la consulta SQL INSERT
    $consulta_insert = $conexion->prepare("INSERT INTO prevendedor (datos, ruta, fecha_asignacion, fecha_visita) VALUES (:datos, :id_ruta, :fecha_asignacion, :fecha_visita)");

// Ejecutar la consulta
$resultado = $consulta_insert->execute(array(
    ':datos' => $id_empleado,
    ':id_ruta' => $id_ruta,
    ':fecha_asignacion' => $fecha_asignacion,
    ':fecha_visita' => $fecha_visita
));


    // Verificar si la inserción fue exitosa
    if ($resultado) {
        echo "Los datos se han insertado correctamente en la tabla prevendedor.";
    } else {
        echo "Hubo un error al insertar los datos en la tabla prevendedor.";
    }
}
?>


<!-- fondo pantalla-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<!--lado izquierdo-->
<section id="pantalla-dividida">
    <div class="body izquierda">
        <!-- asignamos color al fondo y delimitamos el tamanio-->
        <style>
            .body{
            margin:0;
            padding:0;
            }
            .izquierda{
            background: #BEC2DF;
            height: 100vh;
            width:50%;
            }
        </style>
<!-- fin html-->

<?php
echo '<span style="font-size: 25px; color: black; font-family: Verdana; position: relative; top: 10px;">Lista de Prevendedores</span>';
?>

        <br\>
        <div class="table-responsive-sm ">
            <table  class="table table-striped-columns table-hover  table-diseño align-middle table-container texto">
                <thead class="table-light">
                    <caption>
                    </caption>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha Nacimiento</th>
                        <th>CI</th>
                        <th>Telefono</th>
                        <th>Direccion</th>
                        <th>Fecha de Ingreso</th>
                        <th>Garantia</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <!-- Listado de empleados -->
                    <?php foreach ($empleados as $empleado): ?>
                        <tr>
                            <td><?php echo $empleado['nombre']; ?></td>
                            <td><?php echo $empleado['apellido']; ?></td>
                            <td><?php echo $empleado['fecha_nacimiento']; ?></td>
                            <td><?php echo $empleado['CI']; ?></td>
                            <td><?php echo $empleado['telefono']; ?></td>
                            <td><?php echo $empleado['direccion']; ?></td>
                            <td><?php echo $empleado['fecha_ingreso']; ?></td>
                            <td><?php echo $empleado['garantia']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
        <!-- cambia color de la tabla es decir fondo-->
        <style> 
            /*Tamaño*/
            .table-container
            {
                position: relative;
                top: 90px;
                left: 1px;
                right: 10px;
            }
            .table-container th, .table-container td {
                background-color: #BED2D1; /* Color de fondo de las celdas */
                color: black; /* Color del texto */
                padding: 8px;
                border-color: black;
                
            }
            .table-container th {
                background-color: #BED2D1 ; /* Color diferente para el encabezado */
                border-color: black;
            }
        </style>
        <style>  
            .texto{
                font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                font-size: smaller;
            }
        </style>
        
        <!--fin listado-->
    </div>   
    <div class="derecha">
    <style>
     .body{
            margin:0;
            padding:0;
        } 
        .derecha{
        background: white;
        height: 100vh;
        width:50%;
        height: 100vh;
        }
        </style>
      <div class="card">
    <div class="card-header">
        ASIGNAR RUTAS
    </div>
    <style>
        .card-tam {
            width: 20%; /* Reduce el ancho total del formulario */
            margin: 50px auto;
        }
        .card-title {
            margin: 0;
            padding: 10px;
            background: white;
            color: black;
        }
        .card-diseno {
            background: white;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
        }
        .form-group {
            display: flex;
            flex-basis: 22%; /* Ajusta el ancho para dos elementos por línea */
            margin-right: 2%;
            margin-bottom: 10px;
        }
        .form-group:nth-child(2n) {
            margin-right: 0; /* Quita el margen derecho del segundo elemento en cada línea */
        }
        .form-group label {
            width: 40%;
            min-width: 70px;
            margin-right: 10px;
            text-align: right;
        }
        .form-group input {
            width: 60%;
            min-width: 100px;
        }
        .form-group-checkbox {
            flex-basis: 100%; /* Hace que el checkbox ocupe toda la línea */
            display: flex;
            align-items: center;
        }
        .form-group-checkbox label {
            width: auto;
            margin-right: 10px;
        }
    </style>
    
    <div class="card-body card-tam card-title card-diseno">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="Empleado" class="form-label">Empleado</label>
                <select class="form-control" name="Empleado" id="Empleado">
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?php echo $empleado['id_empleado']; ?>"><?php echo $empleado['nombre'] . ' ' . $empleado['apellido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
    <label for="Ruta" class="form-label">Ruta</label>
    <select class="form-control" name="Ruta" id="Ruta">
        <?php foreach ($rutas as $ruta): ?>
            <option value="<?php echo $ruta['idruta']; ?>"><?php echo $ruta['codigo'] . ' - ' . $ruta['municipio']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

            <div class="form-group">
                <label for="Fecha_de_Asignacion" class="form-label">Fecha de Asignacion</label>
                <input type="date" class="form-control" name="Fecha_de_Asignacion" id="Fecha_de_Asignacion" placeholder="Fecha de Asignacion" />
            </div>

            <div class="form-group">
                <label for="Fecha_de_Visita" class="form-label">Fecha de Visita</label>
                <input type="date" class="form-control" name="Fecha_de_Visita" id="Fecha_de_Visita" placeholder="Fecha de Visita" />
            </div>

            <div class="form-group" style="flex-basis: 100%; text-align: center;">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</div>

    <style> 
    #pantalla-dividida{
            display: flex;
        }
    </style>
    </section> 
</body>
</html>
<!-- fin html-->

