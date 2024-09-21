<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stack Game</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .game-container {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 400px;
            max-height: 600px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .stack-container {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column-reverse;
        }

        .block {
            width: 100%;
            height: 30px;
            background-color: #00aaff;
            position: absolute;
            left: 0;
            bottom: 0;
            transition: left 0.2s, width 0.2s;
        }

        .game-over {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .game-over h1 {
            margin: 0 0 20px;
        }

        .game-over button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            background-color: #00aaff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .game-over button:hover {
            background-color: #0088cc;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div id="stack-container" class="stack-container"></div>
        <div id="game-over" class="game-over">
            <h1>Game Over</h1>
            <button id="retry-button">Retry</button>
            <button id="home-button">Home</button>
        </div>
    </div>
    <script>
        const stackContainer = document.getElementById('stack-container');
        const gameOverScreen = document.getElementById('game-over');
        const retryButton = document.getElementById('retry-button');
        const homeButton = document.getElementById('home-button');

        let stack = [];
        let currentBlock;
        let blockWidth = 100;
        let blockHeight = 30;
        let speed = 5;
        let movingRight = true;
        let gameRunning = true;

        function createBlock() {
            const block = document.createElement('div');
            block.className = 'block';
            block.style.width = blockWidth + 'px';
            block.style.left = '0px';
            block.style.bottom = (stack.length * blockHeight) + 'px';
            stackContainer.appendChild(block);
            return block;
        }

        function startGame() {
            gameOverScreen.style.display = 'none';
            stack = [];
            stackContainer.innerHTML = '';
            blockWidth = 100;
            gameRunning = true;
            currentBlock = createBlock();
            requestAnimationFrame(gameLoop);
        }

        function gameLoop() {
            if (!gameRunning) return;

            if (movingRight) {
                currentBlock.style.left = (parseFloat(currentBlock.style.left) || 0) + speed + 'px';
                if (parseFloat(currentBlock.style.left) + blockWidth >= stackContainer.offsetWidth) {
                    movingRight = false;
                }
            } else {
                currentBlock.style.left = (parseFloat(currentBlock.style.left) || 0) - speed + 'px';
                if (parseFloat(currentBlock.style.left) <= 0) {
                    movingRight = true;
                }
            }

            requestAnimationFrame(gameLoop);
        }

        function placeBlock() {
            if (!gameRunning) return;

            if (stack.length === 0) {
                // Place the first block without checking alignment
                stack.push(currentBlock);
                currentBlock = createBlock();
            } else {
                const previousBlock = stack[stack.length - 1];
                const previousLeft = parseFloat(previousBlock.style.left);
                const currentLeft = parseFloat(currentBlock.style.left);

                const overlap = Math.max(0, Math.min(previousLeft + blockWidth, currentLeft + blockWidth) - Math.max(previousLeft, currentLeft));

                if (overlap > 0) {
                    blockWidth = overlap;
                    currentBlock.style.width = blockWidth + 'px';
                    currentBlock.style.left = Math.max(previousLeft, currentLeft) + 'px';
                    stack.push(currentBlock);
                    currentBlock = createBlock();
                } else {
                    endGame();
                }
            }
        }

        function endGame() {
            gameRunning = false;
            gameOverScreen.style.display = 'block';
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === ' ') {
                placeBlock();
            }
        });

        retryButton.addEventListener('click', startGame);
        homeButton.addEventListener('click', () => {
            window.location.href = 'index.php';
        });

        startGame();
    </script>
</body>
</html>

