<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ($_POST['nickname'] == "") {
        $_SESSION['nick'] = "tonko";
    }
    else $_SESSION['nick'] = $_POST['nickname'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super-shy.io</title>
    <link rel="stylesheet" href="css/main.css">

</head>

<body>
    <div class="container">
        <header>
            <h1>
                <a href="index.php" class="title">
                    Super-shy.io
                </a>
            </h1>
        </header>

        <main class="content-outline">

            <div id="gameContain" class="gameContain">
                <canvas id="canvas1" class="canvas"></canvas>
            </div>

            <p>Tvoj alias je
                <span id="alias" class="title">
                    <?php echo $_SESSION['nick'] ?>
                </span>
            </p>
            <div id="msg-block">
            </div>
            <textarea id="msg-text" name="bio" placeholder="Tu mozete pisat spravu..."></textarea>
            <button disabled="true" id="send" onclick="sendMessage()">Odosli spravu!</button>
        </main>

    </div>

    <script src="js/receiver.js"></script>
</body>

</html>