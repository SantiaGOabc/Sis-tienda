<!DOCTYPE html>
<html lang="en">
<head>
    
<?php include("../templates/header.php"); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Selection</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .game {
            position: relative;
            width: 150px;
            height: 150px;
            overflow: hidden;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .game img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .game:hover {
            transform: scale(1.1);
        }

        .game:hover img {
            transform: scale(1.1);
        }

        .game a {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="game">
            <a href="snake.php">
                <img src="snake.jpg" alt="Snake Game">
            </a>
        </div>
        <div class="game">
            <a href="stack.php">
                <img src="stack.jpg" alt="Stacker Game">
            </a>
        </div>
    </div>
</body>
</html>

