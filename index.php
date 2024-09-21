<?php
session_start();

// Verificar si se ingresó el código Konami
if (isset($_SESSION['cod_konami']) && $_SESSION['cod_konami'] === true) {
    // Limpiar la variable de sesión del código Konami
    unset($_SESSION['cod_konami']);
    // Redireccionar al usuario a snake.php
    header('Location: JUEGOS/index.php');
    exit;
}

// Función para verificar la secuencia Konami
function codigo_konami() {
    // codigo Konami: Arriba, Arriba, Abajo, Abajo, Izquierda, Derecha, Izquierda, Derecha, B, A
    $konami_sequence = array("ArrowUp", "ArrowUp", "ArrowDown", "ArrowDown", "ArrowLeft", "ArrowRight", "ArrowLeft", "ArrowRight", "b", "a");

    // Obtener las teclas ingresadas por el usuario
    $keys = json_decode(file_get_contents('php://input'), true)['konami_keys'];

    // Verificar si las teclas ingresadas coinciden con el código Konami
    if ($keys === $konami_sequence) {
        // Establecer una variable de sesión para indicar que se ingresó el código Konami
        $_SESSION['cod_konami'] = true;
    }
}

// Verificar si se envió el formulario de teclas Konami
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    codigo_konami();
}
?>

<?php include("templates/header.php"); ?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-5 fw-bold">BIENVENID@</h1>
                <main class="container mt-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="texto"><?php echo $_SESSION['usuario']; ?></div>
                        <?php if (isset($_SESSION['perfil'])): ?>
                            <img id="konamiImage" src="<?php echo $_SESSION['perfil']; ?>" alt="Imagen de perfil" class="rounded-circle profile-img ms-3">
                        <?php else: ?>
                            <p style="margin-left: 20px;">No hay imagen de perfil</p>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>

<!-- Script para detectar el código Konami -->
<script>
    let cod_konami = [];
    const konamiCode = ["ArrowUp", "ArrowUp", "ArrowDown", "ArrowDown", "ArrowLeft", "ArrowRight", "ArrowLeft", "ArrowRight", "b", "a"];

    document.addEventListener('DOMContentLoaded', function() {
        const konamiImage = document.getElementById('konamiImage');
        if (konamiImage && window.innerWidth >= 769) {
            konamiImage.style.cursor = 'pointer';
            konamiImage.addEventListener('click', function() {
                detectar();
            });
        }
    });

    // Función para comenzar la detección del Konami Code
    function detectar() {
        window.addEventListener('keydown', konamiHandler);
    }

    // Manejador del Konami Code
    function konamiHandler(event) {
        cod_konami.push(event.key);
        cod_konami = cod_konami.slice(-10); // Mantener las últimas 10 teclas

        if (JSON.stringify(cod_konami) === JSON.stringify(konamiCode)) {
            // Enviar las teclas al servidor para verificar
            fetch('index.php', {
                method: 'POST',
                body: JSON.stringify({ konami_keys: cod_konami }),
                headers:{
                    'Content-Type': 'application/json'
                }
            }).then(() => location.reload());
        }
    }
</script>

<!-- Include the footer -->
<?php include("templates/footer.php"); ?>

