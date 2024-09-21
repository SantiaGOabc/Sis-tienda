<?php 
include("../../bd.php");

$usuario = null;
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE idusuario = :idusuario");
    $sentencia->bindParam(":idusuario", $txtID);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);
}

if ($_POST) {
    $txtID = isset($_POST["txtID"]) ? $_POST["txtID"] : "";
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : "";
    $n_usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
    $clave = isset($_POST["clave"]) ? $_POST["clave"] : "";

    $perfil = isset($_FILES['perfil']['name']) ? $_FILES['perfil']['name'] : "";
    $perfil_temp = isset($_FILES['perfil']['tmp_name']) ? $_FILES['perfil']['tmp_name'] : "";
    $carpeta_perfiles = "../../perfiles/";
    $ruta_imagen = $carpeta_perfiles . basename($perfil);
    
    if (!empty($perfil_temp)) {
        if (move_uploaded_file($perfil_temp, $ruta_imagen)) {
            echo "La imagen se subió correctamente.";
        } else {
            echo "Error al subir la imagen.";
        }

        if (!empty($clave)) {
            $clave_encriptada = password_hash($clave, PASSWORD_BCRYPT);
            $sentencia = $conexion->prepare("UPDATE usuario SET nombre = :nombre, correo = :correo, n_usuario = :n_usuario, clave = :clave, perfil = :perfil WHERE idusuario = :idusuario");
            $sentencia->bindParam(":clave", $clave_encriptada);
        } else {
            $sentencia = $conexion->prepare("UPDATE usuario SET nombre = :nombre, correo = :correo, n_usuario = :n_usuario, perfil = :perfil WHERE idusuario = :idusuario");
        }
        $sentencia->bindParam(":perfil", $ruta_imagen);
    } else {
        if (!empty($clave)) {
            $clave_encriptada = password_hash($clave, PASSWORD_BCRYPT);
            $sentencia = $conexion->prepare("UPDATE usuario SET nombre = :nombre, correo = :correo, n_usuario = :n_usuario, clave = :clave WHERE idusuario = :idusuario");
            $sentencia->bindParam(":clave", $clave_encriptada);
        } else {
            $sentencia = $conexion->prepare("UPDATE usuario SET nombre = :nombre, correo = :correo, n_usuario = :n_usuario,  WHERE idusuario = :idusuario");
        }
    }

    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":correo", $correo);
    $sentencia->bindParam(":n_usuario", $n_usuario);
    $sentencia->bindParam(":idusuario", $txtID);

    if ($sentencia->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar el usuario.";
    }
}

include("../../templates/header.php");
?>

</br>
<div class="card">
    <div class="card-header">
        EDITAR USUARIO
    </div>
    <div class="card-body">
        <?php if ($usuario) { ?>
        <!--GUARDAR ARCHIVOS, MULTIMEDIA U OTROS-->
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="txtID" value="<?php echo $usuario['idusuario']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input
                    type="text"
                    class="form-control"
                    name="nombre"
                    id="nombre"
                    aria-describedby="helpId"
                    placeholder="nombre"
                    value="<?php echo $usuario['nombre']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input
                    type="text"
                    class="form-control"
                    name="usuario"
                    id="usuario"
                    aria-describedby="helpId"
                    placeholder="usuario"
                    value="<?php echo $usuario['n_usuario']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Clave (dejar en blanco para no cambiar)</label>
                <input
                    type="password"
                    class="form-control"
                    name="clave"
                    id="clave"
                    aria-describedby="helpId"
                    placeholder="Escriba su contraseña"
                />
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input
                    type="email"
                    class="form-control"
                    name="correo"
                    id="correo"
                    aria-describedby="helpId"
                    placeholder="correo"
                    value="<?php echo $usuario['correo']; ?>"
                />
            </div>
            <div class="mb-3">
                <label for="perfil" class="form-label">Imagen de perfil</label>
                <input type="file" class="form-control" name="perfil" id="perfil" accept="image/*" />
                <?php if (!empty($usuario['perfil'])): ?>
                    <img src="<?php echo $usuario['perfil']; ?>" alt="Perfil" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
        <?php } else { ?>
            <p>Usuario no encontrado.</p>
        <?php } ?>
    </div>
</div>

<?php include("../../templates/footer.php");?>
