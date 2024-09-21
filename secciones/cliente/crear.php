<?php
include("../../bd.php");

// Obtener el último código generado
$sentencia_ultimo_codigo = $conexion->query("SELECT MAX(codigo) as ultimo_codigo FROM cliente");
$ultimo_codigo = $sentencia_ultimo_codigo->fetch(PDO::FETCH_ASSOC)['ultimo_codigo'];

// Generar el próximo código
if ($ultimo_codigo === null) {
    $siguiente_codigo = '000001';
} else {
    $siguiente_codigo = str_pad((int)$ultimo_codigo + 1, 6, '0', STR_PAD_LEFT);
}

// Obtener los datos de prevendedores activos
$sentencia_prevendedores = $conexion->prepare("
    SELECT p.idprevendedor, e.nombre
    FROM prevendedor p 
    INNER JOIN empleados e ON p.datos = e.id_empleado
    WHERE e.rol = 3 AND p.activo = 1 AND e.activo = 1
");
$sentencia_prevendedores->execute();
$lista_prevendedores = $sentencia_prevendedores->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
    $razonSocial = isset($_POST["razonSocial"]) ? $_POST["razonSocial"] : "";
    $NIT_CI = isset($_POST["NIT_CI"]) ? $_POST["NIT_CI"] : "";
    $Direccion = isset($_POST["Direccion"]) ? $_POST["Direccion"] : "";
    $Referencia = isset($_POST["Referencia"]) ? $_POST["Referencia"] : "";
    $telf = isset($_POST["telf"]) ? $_POST["telf"] : "";
    $codigo = $siguiente_codigo; // Usamos el código generado automáticamente
    $activo = isset($_POST["activo"]) ? 1 : 0;
    $prevendedor_idprevendedor = isset($_POST["prevendedor_idprevendedor"]) ? $_POST["prevendedor_idprevendedor"] : "";

    $imagen_tienda = $_FILES['imagen_tienda']['name'];
    $imagen_temp = $_FILES['imagen_tienda']['tmp_name'];
    $carpeta_imagenes = "../../imagenes_tienda/";
    $ruta_imagen = $carpeta_imagenes . basename($imagen_tienda);

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
        header("Location: index.php");
        exit;
    } else {
        echo "Error al insertar el cliente.";
    }
}

include("../../templates/header.php");
?>

<br>
<div class="card">
    <div class="card-header">
        DATOS DEL CLIENTE
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Listado de prevendedores -->
            <div class="mb-3">
                <label for="prevendedor_idprevendedor" class="form-label">Seleccionar Prevendedor:</label>
                <select class="form-select" name="prevendedor_idprevendedor" id="prevendedor_idprevendedor">
                    <option value="">Selecciona un prevendedor</option>
                    <?php foreach ($lista_prevendedores as $prevendedor): ?>
                        <option value="<?php echo $prevendedor['idprevendedor']; ?>"><?php echo $prevendedor['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre"/>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido"/>
            </div>
            <div class="mb-3">
                <label for="razonSocial" class="form-label">Razón Social</label>
                <input type="text" class="form-control" name="razonSocial" id="razonSocial" placeholder="Razón Social"/>
            </div>
            <div class="mb-3">
                <label for="NIT_CI" class="form-label">NIT/CI</label>
                <input type="text" class="form-control" name="NIT_CI" id="NIT_CI" placeholder="NIT/CI"/>
            </div>
            <div class="mb-3">
                <label for="Direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="Direccion" id="Direccion" placeholder="Dirección"/>
            </div>
            <div class="mb-3">
                <label for="Referencia" class="form-label">Referencia</label>
                <input type="text" class="form-control" name="Referencia" id="Referencia" placeholder="Referencia"/>
            </div>
            <div class="mb-3">
                <label for="telf" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telf" id="telf" placeholder="Teléfono"/>
            </div>
            <div class="mb-3">
                <label for="activo" class="form-label">Activo</label>
                <input type="checkbox" name="activo" id="activo"/>
            </div>
            <div class="mb-3">
                <label for="imagen_tienda" class="form-label">Imagen de tienda</label>
                <input type="file" class="form-control" name="imagen_tienda" id="imagen_tienda" accept="image/*"/>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
