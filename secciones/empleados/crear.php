<?php
session_start();
include("../../bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : "";
    $ci = isset($_POST['CI']) ? $_POST['CI'] : "";
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : "";
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : "";
    $garantia = isset($_POST['garantia']) ? $_POST['garantia'] : "";
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : "";
    $fecha_ingreso = isset($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : "";
    $correo = isset($_POST['correo']) ? $_POST['correo'] : "";
    $rol = isset($_POST['rol']) ? $_POST['rol'] : "";
    $activo = isset($_POST['activo']) ? $_POST['activo'] : "";

    $sentencia = $conexion->prepare("INSERT INTO empleados (nombre, apellido, CI, direccion, telefono, garantia, fecha_nacimiento, fecha_ingreso, correo, rol, activo) VALUES (:nombre, :apellido, :CI, :direccion, :telefono, :garantia, :fecha_nacimiento, :fecha_ingreso, :correo, :rol, :activo)");
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':apellido', $apellido);
    $sentencia->bindParam(':CI', $ci);
    $sentencia->bindParam(':direccion', $direccion);
    $sentencia->bindParam(':telefono', $telefono);
    $sentencia->bindParam(':garantia', $garantia);
    $sentencia->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $sentencia->bindParam(':fecha_ingreso', $fecha_ingreso);
    $sentencia->bindParam(':correo', $correo);
    $sentencia->bindParam(':rol', $rol);
    $sentencia->bindParam(':activo', $activo);

    if ($sentencia->execute()) {
        echo "Empleado creado exitosamente.";
    } else {
        echo "Error al crear el empleado.";
    }
}

include("../../templates/header.php");
?>

<!-- Formulario para crear un nuevo empleado -->
<div class="container">
    <div class="card">
        <div class="card-header">
            Crear Nuevo Empleado
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="CI">CI:</label>
                    <input type="text" class="form-control" id="CI" name="CI" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="form-group">
                    <label for="garantia">Garantía:</label>
                    <input type="number" step="0.01" class="form-control" id="garantia" name="garantia" required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-group">
                    <label for="fecha_ingreso">Fecha de Ingreso:</label>
                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select form-select-lg" name="rol" id="rol">
                    <option selected>Seleccione uno</option>
                    <option value="1">Administrador</option>
                    <option value="2">Distribuidor</option>
                    <option value="3">Prevendedor</option>
                    <option value="4">Almacenero</option>
                    <option value="5">Limpieza</option>
                </select>
            </div>
                <div class="form-group">
                    <label for="activo">Activo:</label>
                    <input type="checkbox" id="activo" name="activo" value="1">
                </div>
                <button type="submit" class="btn btn-primary">Crear</button>
            </form>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
