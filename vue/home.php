<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ca roule ma poule</title>
    <link href="/Public/style/home.css" rel="stylesheet" />
</head>

<body>
    <?php
    // home.php
    require 'vendor/autoload.php'; // ajustez le chemin en conséquence

    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/composent'); // ajustez le chemin en conséquence
    $twig = new \Twig\Environment($loader);
    // Affichage de la navbar
    echo $twig->render('header.twig'); ?>


    <div class="div vitrine">

    </div>
    
</body>

</html>