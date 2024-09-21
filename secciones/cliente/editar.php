<?php 
include("../../bd.php");

$cliente = null;
if (isset($_GET['id'])) {
    $txtID = $_GET['id'];

    $sentencia = $conexion->prepare("SELECT * FROM cliente WHERE idcliente = :idcliente");
    $sentencia->bindParam(":idcliente", $txtID);
    $sentencia->execute();
    $cliente = $sentencia->fetch(PDO::FETCH_ASSOC);
}

if ($_POST) {
    $txtID = isset($_POST["txtID"]) ? $_POST["txtID"] : "";
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
    $razonSocial = isset($_POST["razonSocial"]) ? $_POST["razonSocial"] : "";
    $NIT_CI = isset($_POST["NIT_CI"]) ? $_POST["NIT_CI"] : "";
    $Direccion = isset($_POST["Direccion"]) ? $_POST["Direccion"] : "";
    $Referencia = isset($_POST["Referencia"]) ? $_POST["Referencia"] : "";
    $telf = isset($_POST["telf"]) ? $_POST["telf"] : "";
    $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";
    $activo = isset($_POST["activo"]) ? 1 : 0;
    $imagen_tienda = isset($_FILES['imagen_tienda']['name']) ? $_FILES['imagen_tienda']['name'] : "";
    $imagen_temp = isset($_FILES['imagen_tienda']['tmp_name']) ? $_FILES['imagen_tienda']['tmp_name'] : "";
    $carpeta_imagenes = "../../imagenes_tienda/";
    $ruta_imagen = $carpeta_imagenes . basename($imagen_tienda);

    if (!empty($imagen_temp)) {
        if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
            $sentencia = $conexion->prepare("UPDATE cliente SET nombre = :nombre, apellido = :apellido, razonSocial = :razonSocial, NIT_CI = :NIT_CI, Direccion = :Direccion, Referencia = :Referencia, telf = :telf, codigo = :codigo, activo = :activo, imagen_tienda = :imagen_tienda WHERE idcliente = :idcliente");
            $sentencia->bindParam(":imagen_tienda", $ruta_imagen);
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        $sentencia = $conexion->prepare("UPDATE cliente SET nombre = :nombre, apellido = :apellido, razonSocial = :razonSocial, NIT_CI = :NIT_CI, Direccion = :Direccion, Referencia = :Referencia, telf = :telf, codigo = :codigo, activo = :activo WHERE idcliente = :idcliente");
    }

    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":apellido", $apellido);
    $sentencia->bindParam(":razonSocial", $razonSocial);
    $sentencia->bindParam(":NIT_CI", $NIT_CI);
    $sentencia->bindParam(":Direccion", $Direccion);
    $sentencia->bindParam(":Referencia", $Referencia);
    $sentencia->bindParam(":telf", $telf);
    $sentencia->bindParam(":codigo", $codigo);
    $sentencia->bindParam(":activo", $activo);
    $sentencia->bindParam(":idcliente", $txtID);

    if ($sentencia->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar el cliente.";
    }
}

include("../../templates/header.php");
?>

</br>
<div class="card">
    <div class="card-header">
        EDITAR CLIENTE
    </div>
    <div class="card-body">
        <?php if ($cliente) { ?>
        <!--GUARDAR ARCHIVOS, MULTIMEDIA U OTROS-->
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="txtID" value="<?php echo $cliente['idcliente']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input
                    type="text"
                    class="form-control"
                    name="nombre"
                    id="nombre"
                    aria-describedby="helpId"
                    placeholder="nombre"
                    value="<?php echo $cliente['nombre']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input
                    type="text"
                    class="form-control"
                    name="apellido"
                    id="apellido"
                    aria-describedby="helpId"
                    placeholder="apellido"
                    value="<?php echo $cliente['apellido']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="razonSocial" class="form-label">Razón Social</label>
                <input
                    type="text"
                    class="form-control"
                    name="razonSocial"
                    id="razonSocial"
                    aria-describedby="helpId"
                    placeholder="razonSocial"
                    value="<?php echo $cliente['razonSocial']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="NIT_CI" class="form-label">NIT/CI</label>
                <input
                    type="text"
                    class="form-control"
                    name="NIT_CI"
                    id="NIT_CI"
                    aria-describedby="helpId"
                    placeholder="NIT_CI"
                    value="<?php echo $cliente['NIT_CI']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="Direccion" class="form-label">Dirección</label>
                <input
                    type="text"
                    class="form-control"
                    name="Direccion"
                    id="Direccion"
                    aria-describedby="helpId"
                    placeholder="Direccion"
                    value="<?php echo $cliente['Direccion']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="Referencia" class="form-label">Referencia</label>
                <input
                    type="text"
                    class="form-control"
                    name="Referencia"
                    id="Referencia"
                    aria-describedby="helpId"
                    placeholder="Referencia"
                    value="<?php echo $cliente['Referencia']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="telf" class="form-label">Teléfono</label>
                <input
                    type="text"
                    class="form-control"
                    name="telf"
                    id="telf"
                    aria-describedby="helpId"
                    placeholder="telf"
                    value="<?php echo $cliente['telf']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input
                    type="text"
                    class="form-control"
                    name="codigo"
                    id="codigo"
                    aria-describedby="helpId"
                    placeholder="codigo"
                    value="<?php echo $cliente['codigo']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="imagen_tienda" class="form-label">Imagen de Tienda</label>
                <input type="file" class="form-control" name="imagen_tienda" id="imagen_tienda" accept="image/*" />
                <?php if (!empty($cliente['imagen_tienda'])): ?>
                    <img src="<?php echo $cliente['imagen_tienda']; ?>" alt="Tienda" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="activo" class="form-label">Activo</label>
                <input type="checkbox" name="activo" id="activo" <?php echo $cliente['activo'] ? 'checked' : ''; ?> />
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
        <?php } else { ?>
            <p>Cliente no encontrado.</p>
        <?php } ?>
    </div>
</div>

<?php include("../../templates/footer.php");?>
