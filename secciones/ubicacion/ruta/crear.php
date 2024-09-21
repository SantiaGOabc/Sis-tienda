<?php 
include("../../../bd.php");

if($_POST) {
    $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";
    $municipio = isset($_POST["municipio"]) ? $_POST["municipio"] : "";
    
    $imagen = $_FILES['imagen']['name'];
    $imagen_temp = $_FILES['imagen']['tmp_name'];
    $carpeta_imagenes = "../../rutas/";
    $ruta_imagen = $carpeta_imagenes . basename($imagen);
    
    if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
        echo "La imagen se subió correctamente.";
    } else {
        echo "Error al subir la imagen.";
    }

    $sentencia = $conexion->prepare("INSERT INTO rutas (codigo, municipio, imagen) VALUES (:codigo, :municipio, :imagen)");
    $sentencia->bindParam(":codigo", $codigo);
    $sentencia->bindParam(":municipio", $municipio);
    $sentencia->bindParam(":imagen", $ruta_imagen);

    if ($sentencia->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al insertar la ruta.";
    }
}

include("../../../templates/header.php");
?>

<br>
<div class="card">
    <div class="card-header">
        DATOS DE LA RUTA
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Código de la ruta"/>
            </div>
            <div class="mb-3">
                <label for="municipio" class="form-label">Municipio</label>
                <input type="text" class="form-control" name="municipio" id="municipio" placeholder="Municipio"/>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*"/>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

