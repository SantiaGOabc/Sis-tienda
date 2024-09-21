<?php
session_start();
include("../../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario actual
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Verificar si se ha proporcionado el ID de la ruta a editar
if (isset($_GET['idruta'])) {
    $idruta = $_GET['idruta'];

    // Obtener los datos actuales de la ruta
    $sentencia = $conexion->prepare("SELECT * FROM rutas WHERE idruta = :idruta");
    $sentencia->bindParam(":idruta", $idruta);
    $sentencia->execute();
    $ruta = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Verificar si se envió el formulario de actualización
    if ($_POST) {
        $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";
        $municipio = isset($_POST["municipio"]) ? $_POST["municipio"] : "";

        // Verificar si se subió una nueva imagen
        if ($_FILES['imagen']['name']) {
            $imagen = $_FILES['imagen']['name'];
            $imagen_temp = $_FILES['imagen']['tmp_name'];
            $carpeta_imagenes = "../../imagenes/";
            $ruta_imagen = $carpeta_imagenes . basename($imagen);

            if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
                // Eliminar la imagen anterior si existe
                if (!empty($ruta['imagen'])) {
                    unlink($ruta['imagen']);
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            // Conservar la imagen existente si no se selecciona una nueva
            $ruta_imagen = $ruta['imagen'];
        }

        // Actualizar los datos en la base de datos
        $sentencia_actualizar = $conexion->prepare("UPDATE rutas SET codigo = :codigo, municipio = :municipio, imagen = :imagen WHERE idruta = :idruta");
        $sentencia_actualizar->bindParam(":codigo", $codigo);
        $sentencia_actualizar->bindParam(":municipio", $municipio);
        $sentencia_actualizar->bindParam(":imagen", $ruta_imagen);
        $sentencia_actualizar->bindParam(":idruta", $idruta);

        if ($sentencia_actualizar->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error al actualizar la ruta.";
        }
    }
}

include("../../../templates/header.php");
?>

<!-- Formulario de Edición de Ruta -->
<br>
<div class="card">
    <div class="card-header">    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" name="codigo" id="codigo" value="<?php echo $ruta['codigo']; ?>" placeholder="Código de la ruta"/>
            </div>
            <div class="mb-3">
                <label for="municipio" class="form-label">Municipio</label>
                <input type="text" class="form-control" name="municipio" id="municipio" value="<?php echo $ruta['municipio']; ?>" placeholder="Municipio"/>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen Actual</label><br>
                <?php if (!empty($ruta['imagen'])): ?>
                    <img src="<?php echo $ruta['imagen']; ?>" alt="Imagen Actual" width="200px">
                <?php else: ?>
                    No hay imagen
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="imagen_nueva" class="form-label">Subir Nueva Imagen</label>
                <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*"/>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>



