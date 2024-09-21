<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Snake</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #2d2d2d;
            color: #fff;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        #gameContainer {
            text-align: center;
        }
        canvas {
            background-color: #000;
            display: block;
            border: 2px solid #00FF00;
        }
        .game-over {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .game-over h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .game-over button {
            background-color: #00FF00;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .game-over button:hover {
            background-color: #00cc00;
        }
        @media (max-width: 600px) {
            canvas {
                width: 100vw;
                height: 100vw;
            }
            .game-over h1 {
                font-size: 18px;
            }
            .game-over button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div id="gameContainer">
    <canvas id="gameCanvas"></canvas>
    <div class="game-over" id="gameOver">
        <h1>Game Over</h1>
        <button onclick="retryGame()">Volver a Intentar</button>
        <button onclick="goHome()">Home</button>
    </div>
</div>

<script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");

    // Variables del juego
    let box = 20;
    let snake = [{ x: 9 * box, y: 10 * box }];
    let food = { x: Math.floor(Math.random() * 19 + 1) * box, y: Math.floor(Math.random() * 19 + 1) * box };
    let score = 0;
    let d;
    let game;

    // Configuración para ajustar el tamaño del canvas
    function resizeCanvas() {
        const size = Math.min(window.innerWidth, window.innerHeight) - 40;
        canvas.width = size;
        canvas.height = size;
        box = canvas.width / 20;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    // Control de la dirección mediante el teclado
    document.addEventListener("keydown", direction);
    document.addEventListener("touchstart", handleTouchStart, false);
    document.addEventListener("touchmove", handleTouchMove, false);

    let xDown = null;
    let yDown = null;

    function getTouches(evt) {
        return evt.touches || evt.originalEvent.touches;
    }

    function handleTouchStart(evt) {
        const firstTouch = getTouches(evt)[0];
        xDown = firstTouch.clientX;
        yDown = firstTouch.clientY;
    }

    function handleTouchMove(evt) {
        if (!xDown || !yDown) {
            return;
        }

        const xUp = evt.touches[0].clientX;
        const yUp = evt.touches[0].clientY;

        const xDiff = xDown - xUp;
        const yDiff = yDown - yUp;

        if (Math.abs(xDiff) > Math.abs(yDiff)) {
            if (xDiff > 0 && d != "RIGHT") {
                d = "LEFT";
            } else if (xDiff < 0 && d != "LEFT") {
                d = "RIGHT";
            }
        } else {
            if (yDiff > 0 && d != "DOWN") {
                d = "UP";
            } else if (yDiff < 0 && d != "UP") {
                d = "DOWN";
            }
        }
        xDown = null;
        yDown = null;
    }

    function direction(event) {
        let key = event.keyCode;
        if (key == 37 && d != "RIGHT") d = "LEFT";
        else if (key == 38 && d != "DOWN") d = "UP";
        else if (key == 39 && d != "LEFT") d = "RIGHT";
        else if (key == 40 && d != "UP") d = "DOWN";
    }

    function draw() {
        ctx.fillStyle = "#000";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < snake.length; i++) {
            ctx.fillStyle = (i == 0) ? "#00FF00" : "#FFFFFF";
            ctx.fillRect(snake[i].x, snake[i].y, box, box);
            ctx.strokeStyle = "#000";
            ctx.strokeRect(snake[i].x, snake[i].y, box, box);
        }

        ctx.fillStyle = "#FF0000";
        ctx.fillRect(food.x, food.y, box, box);

        let snakeX = snake[0].x;
        let snakeY = snake[0].y;

        if (d == "LEFT") snakeX -= box;
        if (d == "UP") snakeY -= box;
        if (d == "RIGHT") snakeX += box;
        if (d == "DOWN") snakeY += box;

        if (snakeX == food.x && snakeY == food.y) {
            score++;
            food = { x: Math.floor(Math.random() * 19 + 1) * box, y: Math.floor(Math.random() * 19 + 1) * box };
        } else {
            snake.pop();
        }

        let newHead = { x: snakeX, y: snakeY };

        if (snakeX < 0 || snakeY < 0 || snakeX >= canvas.width || snakeY >= canvas.height || collision(newHead, snake)) {
            clearInterval(game);
            showGameOver();
        }

        snake.unshift(newHead);

        ctx.fillStyle = "#FFF";
        ctx.font = "45px Changa one";
        ctx.fillText(score, 2 * box, 1.6 * box);
    }

    function collision(head, array) {
        for (let i = 0; i < array.length; i++) {
            if (head.x == array[i].x && head.y == array[i].y) {
                return true;
            }
        }
        return false;
    }

    function showGameOver() {
        document.getElementById('gameOver').style.display = 'block';
    }

    function retryGame() {
        document.getElementById('gameOver').style.display = 'none';
        snake = [{ x: 9 * box, y: 10 * box }];
        score = 0;
        d = null;
        food = { x: Math.floor(Math.random() * 19 + 1) * box, y: Math.floor(Math.random() * 19 + 1) * box };
        game = setInterval(draw, 100);
    }

    function goHome() {
        window.location.href = '../../index.php';
    }

    // Iniciar el juego
    retryGame();
</script>

</body>
</html>

