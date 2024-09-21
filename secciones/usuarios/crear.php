<?php
include("../../bd.php");

// Obtener los datos de empleado que no tienen un usuario activo
$sentencia_empleados = $conexion->prepare("
    SELECT e.id_empleado, e.nombre, e.correo, e.rol 
    FROM empleados e
    LEFT JOIN usuario u ON e.id_empleado = u.empleado AND u.activo = 1
    WHERE e.rol < 4 AND e.activo = 1 AND u.empleado IS NULL
");
$sentencia_empleados->execute();
$lista_empleados = $sentencia_empleados->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : "";
    $usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
    $clave = isset($_POST["clave"]) ? $_POST["clave"] : "";
    $empleado = isset($_POST["empleado"]) ? $_POST["empleado"] : "";
    
    $perfil = $_FILES['perfil']['name'];
    $perfil_temp = $_FILES['perfil']['tmp_name'];
    $carpeta_perfiles = "../../perfiles/";
    $ruta_imagen = $carpeta_perfiles . basename($perfil);
    
    if (move_uploaded_file($perfil_temp, $ruta_imagen)) {
        echo "La imagen se subió correctamente.";
    } else {
        echo "Error al subir la imagen.";
    }
    
    $clave_encriptada = password_hash($clave, PASSWORD_BCRYPT);

    $sentencia = $conexion->prepare("INSERT INTO usuario (nombre, correo, n_usuario, clave, perfil, empleado) VALUES (:nombre, :correo, :usuario, :clave_encriptada, :perfil, :empleado)");
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":correo", $correo);
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":clave_encriptada", $clave_encriptada); 
    $sentencia->bindParam(":perfil", $ruta_imagen);
    $sentencia->bindParam(":empleado", $empleado);

    if ($sentencia->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al insertar el usuario.";
    }
}

include("../../templates/header.php");
?>

<br>
<div class="card">
    <div class="card-header">
        DATOS DEL USUARIO
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Listado de empleados -->
            <div class="mb-3">
                <label for="empleado" class="form-label">Seleccionar Empleado:</label>
                <select class="form-select" name="empleado" id="empleado" onchange="actualizarDatosEmpleado()">
                    <option value="">Selecciona un empleado</option>
                    <?php foreach ($lista_empleados as $empleado): ?>
                        <option value="<?php echo $empleado['id_empleado']; ?>" data-nombre="<?php echo $empleado['nombre']; ?>" data-correo="<?php echo $empleado['correo']; ?>"><?php echo $empleado['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="nombre"/>
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="usuario"/>
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Clave</label>
                <input type="password" class="form-control" name="clave" id="clave" placeholder="Escriba su contraseña"/>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" id="correo" placeholder="correo"/>
            </div>
            <div class="mb-3">
                <label for="perfil" class="form-label">Imagen de perfil</label>
                <input type="file" class="form-control" name="perfil" id="perfil" accept="image/*"/>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<script>
function actualizarDatosEmpleado() {
    var empleadoSelect = document.getElementById('empleado');
    var selectedOption = empleadoSelect.options[empleadoSelect.selectedIndex];
    var nombre = selectedOption.getAttribute('data-nombre');
    var correo = selectedOption.getAttribute('data-correo');
    
    document.getElementById('nombre').value = nombre;
    document.getElementById('correo').value = correo;
}
</script>

<?php include("../../templates/footer.php");?>
