<?php
session_start();
include("../../bd.php");

if ($_POST) {
    // Recibimos los datos del formulario
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
    $razonSocial = isset($_POST["razonSocial"]) ? $_POST["razonSocial"] : "";
    $NIT_CI = isset($_POST["NIT_CI"]) ? $_POST["NIT_CI"] : "";
    $Direccion = isset($_POST["Direccion"]) ? $_POST["Direccion"] : "";
    $Referencia = isset($_POST["Referencia"]) ? $_POST["Referencia"] : "";
    $telf = isset($_POST["telf"]) ? $_POST["telf"] : "";
    $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";
    $activo = isset($_POST["activo"]) ? $_POST["activo"] : "";
    $prevendedor_idprevendedor = isset($_SESSION['idprevendedor']) ? $_SESSION['idprevendedor'] : null;

    $imagen_tienda = $_FILES['imagen_tienda']['name'];
    $imagen_temp = $_FILES['imagen_tienda']['tmp_name'];
    $carpeta_imagenes = "../../imagenes_tienda/";
    $ruta_imagen = $carpeta_imagenes . basename($imagen_tienda);

        // Movemos la imagen a la carpeta especificada
        if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
            echo "La imagen se subió correctamente.";
        } else {
            echo "Error al subir la imagen.";
        }
            $sentencia = $conexion->prepare("INSERT INTO cliente (nombre, apellido, razonSocial, NIT_CI, Direccion, Referencia, telf, codigo, activo, prevendedor_idprevendedor, imagen_tienda) VALUES (:nombre, :apellido, :razonSocial, :NIT_CI, :Direccion, :Referencia, :telf, :codigo, :activo, :prevendedor_idprevendedor, :imagen_tienda)");

            $sentencia->bindParam(":nombre", $nombre);
            $sentencia->bindParam(":apellido", $apellido);
            $sentencia->bindParam(":razonSocial", $razonSocial);
            $sentencia->bindParam(":NIT_CI", $NIT_CI);
            $sentencia->bindParam(":Direccion", $Direccion);
            $sentencia->bindParam(":Referencia", $Referencia);
            $sentencia->bindParam(":telf", $telf);
            $sentencia->bindParam(":codigo", $codigo);
            $sentencia->bindParam(":activo", $activo);
            $sentencia->bindParam(":prevendedor_idprevendedor", $prevendedor_idprevendedor);
            $sentencia->bindParam(":imagen_tienda", $ruta_imagen);

            if ($sentencia->execute()) {
                header("Location: index_p.php");
                exit;
            } else {
                echo "Error al insertar el cliente.";
            }
}
include ("../../templates/header.php");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Agregar Cliente</title>
</head>
<body>
<div class="container">
    <h1 class="mt-5">Agregar Cliente</h1>
    <form action="crear_p.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="razonSocial" class="form-label">Razón Social</label>
            <input type="text" class="form-control" id="razonSocial" name="razonSocial" required>
        </div>
        <div class="mb-3">
            <label for="NIT_CI" class="form-label">NIT/CI</label>
            <input type="text" class="form-control" id="NIT_CI" name="NIT_CI" required>
        </div>
        <div class="mb-3">
            <label for="Direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="Direccion" name="Direccion" required>
        </div>
        <div class="mb-3">
            <label for="Referencia" class="form-label">Referencia</label>
            <input type="text" class="form-control" id="Referencia" name="Referencia" required>
        </div>
        <div class="mb-3">
            <label for="telf" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telf" name="telf" required>
        </div>
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>
        <div class="mb-3">
            <label for="activo" class="form-label">Activo</label>
            <select class="form-select" id="activo" name="activo" required>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen_tienda" class="form-label">Imagen de la tienda</label>
            <input type="file" class="form-control" id="imagen_tienda" name="imagen_tienda" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Cliente</button>
    </form>
</div>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4JQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
