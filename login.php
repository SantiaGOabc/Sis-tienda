<?php
session_start();
$mensaje = "";
if ($_POST) {
    include("bd.php");

    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Preparamos la consulta SQL
    $sentencia = $conexion->prepare("
        SELECT u.*, e.rol, d.ruta, d.iddistribuidor, p.idprevendedor, p.ruta
        FROM `usuario` u 
        INNER JOIN `empleados` e ON u.empleado = e.id_empleado
        LEFT JOIN `distribuidor` d ON e.id_empleado = d.datos 
        LEFT JOIN `prevendedor` p ON e.id_empleado = p.datos 
        WHERE n_usuario=:usuario and u.activo = 1
    ");
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->execute();

    $lista_usuarios = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Verificamos si se encontró el usuario y la contraseña es correcta
    if ($lista_usuarios && password_verify($clave, $lista_usuarios["clave"])) {
        $_SESSION['usuario'] = $lista_usuarios["n_usuario"];
        $_SESSION['perfil'] = $lista_usuarios["perfil"];
        $_SESSION['rol'] = $lista_usuarios["rol"];  // Almacenamos el rol en la sesión

        if ($lista_usuarios["rol"] == 2) { // Si el usuario es un distribuidor
            $_SESSION['dis_ruta'] = $lista_usuarios["ruta"];  // Almacenamos la ruta en la sesión
        }
        if ($lista_usuarios["rol"] == 3) { // Si el usuario es un prevendedor
            $_SESSION['prev_ruta'] = $lista_usuarios["ruta"];  // Almacenamos la ruta en la sesión
        }
        if ($lista_usuarios["rol"] == 2) { 
            $_SESSION['iddistribuidor'] = $lista_usuarios["iddistribuidor"]; 
        }
        if ($lista_usuarios["rol"] == 3) { // Si el usuario tiene el rol de prevendedor
            $_SESSION['idprevendedor'] = $lista_usuarios["idprevendedor"];
        }

        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var imageToAnimate = document.getElementById('imageToAnimate');
                    imageToAnimate.classList.add('animate__lightSpeedOutRight');
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 1000); 
                });
              </script>";
    } else {
        $mensaje = "Error: Usuario o contraseña incorrecta";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #1f293a;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login {
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            -webkit-animation: escala-centro 0.8s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
            animation: escala-centro 0.8s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }
        .login .form-label {
            color: #333;
        }
        .alert {
            margin-bottom: 20px;
        }
        .login img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto; 
            max-width: 150px; 
        }
        @-webkit-keyframes escala-centro {
            0% {
                -webkit-transform: scale(0);
                        transform: scale(0);
                opacity: 1;
            }
            100% {
                -webkit-transform: scale(1);
                        transform: scale(1);
                opacity: 1;
            }
        }
        @keyframes escala-centro {
            0% {
                -webkit-transform: scale(0);
                        transform: scale(0);
                opacity: 1;
            }
            100% {
                -webkit-transform: scale(1);
                        transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
<main>
    <div class="login">
        <?php if (!empty($mensaje)) { ?>
            <div class="alert alert-danger" role="alert">
                <strong><?php echo $mensaje; ?></strong>
            </div>
        <?php } ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Ingrese su usuario" required/>
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" name="clave" id="clave" placeholder="Escriba su contraseña" required/>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        <!-- Imagen con animación -->
        <img id="imageToAnimate" src="logo.jpg" alt="Imagen animada" class="animate__animated animate__lightSpeedInLeft"/>
    </div>
</main>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4JQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>
</html>
