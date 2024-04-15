<?php
session_start();

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
            <form action="supershy-io.php" method="post">
                <div class="form-input">
                    <p>Zadaj svoj nickname:</p>
                    <?php
                    if (isset($_SESSION['nick']) && $_SESSION['nick'] != '') {
                        echo '<input type="text" value=' . $_SESSION['nick'] . ' name="nickname" id="nickname" placeholder="toma18vsecezet">';
                    } else
                        echo '<input type="text" name="nickname" id="nickname" placeholder="toma18vsecezet">';
                    ?>
                </div>
                <div class="form-input">
                    <p>Dĺžka hry:</p>
                    <select name="game-length-seconds" id="game-length-seconds">
                        <option value="120">2 min</option>
                        <option value="300">5 min</option>
                        <option value="600">10 min</option>
                    </select>
                </div>
                <div class="form-input">
                    <button id="start-game" type="submit">Začni hru!</button>
                </div>
            </form>
        </main>

    </div>
</body>

</html>