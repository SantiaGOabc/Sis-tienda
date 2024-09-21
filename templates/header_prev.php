<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$url_base = "http://localhost:3000/";
?>

<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        .nav-custom {
            background-color: black;
        }
        .margen {
            margin-top: 20px;
        }
        .nav-item .nav-link {
            color: #B8C0FF;
        }
        .nav-item .nav-link.active {
            font-weight: 700;
            font-size: larger;
        }
        .nav-item .nav-link:hover {
            color: #ffffff;
        }
        .texto {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-size: xx-large;
        }
        .profile-img {
            width: 50px;
            height: 50px;
            margin-left: 20px;
        }
        @media (max-width: 768px) {
            .profile-img {
                margin-left: 10px;
            }
            .navbar-nav {
                text-align: center;
            }
        }
        @media (min-width: 992px) {
            .navbar-brand {
                display: block !important;
            }
            .nav-item.d-lg-none {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<header>
</header>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $url_base;?>index.php">TIENDAS MOVIL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>index.php">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Cliente
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo $url_base;?>secciones/cliente/index_p.php">LISTADO</a></li>
                        <li><a class="dropdown-item" href="<?php echo $url_base;?>secciones/cliente/crear_p.php">NUEVO</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pedido
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo $url_base;?>secciones/pedidos/listado.php">Listado</a></li>
                        <li><a class="dropdown-item" href="<?php echo $url_base;?>secciones/pedidos/registar.php">Registrar</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>cerrar.php">Cerrar Sesion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-NyLpYa+FskMtmPqfw71xBGRis+SYddluY0XqJElElImVnZ3mb1T9I1Lco9HE5fXU" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var navbarToggler = document.querySelector('.navbar-toggler');
        var navbarBrand = document.querySelector('.navbar-brand');

        navbarToggler.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                navbarBrand.classList.toggle('d-none');
            }
        });
    });
</script>
</body>
</html>
