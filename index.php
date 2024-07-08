<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

    <?php
        var_dump($_SESSION);
        if (isset($_SESSION['error'])) {
            echo '<p>'.$_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p>'.$_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
    ?>
    <form action="./traitement.php" method="post">
        <label for="mail">Entrez votre mail :</label>
        <input type="email" name="mail" id="mailInput">

        <label for="password">Entrez votre mot de passe :</label>
        <input type="password" name="password" id="passwordinput">

        <input type="submit" name="connectForm" value="Se connecter">
    </form>

    <form action="./traitement.php" method="post">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudoInput">

        <label for="mail">E-Mail</label>
        <input type="email" name="mail" id="mailInput">

        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="passwordInput">

        <label for="password2">Comfiramation du mot de passe:</label>
        <input type="password" name="password1" id="passwordConfirm">

        <input type="submit" name="subForm" value="S'inscrire">
    </form>
</body>
</html>