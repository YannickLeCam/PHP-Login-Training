<?php
session_start();
$HOST = "localhost"; //DB IP
$DB = "login_test"; //DB name
$USER = "root"; //DB username
$PASS = "";  // DB password

$pdo = new PDO(
    "mysql:host=".$HOST.";dbname=".$DB.";charset=UTF8",$USER,$PASS
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['subForm'])) {
    //Dans le cas ou un formulaire d'inscription vient d'etre soumit
    $pseudo=filter_input(INPUT_POST,'pseudo',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $mail=filter_input(INPUT_POST,'mail',FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_VALIDATE_EMAIL);
    $pass=filter_input(INPUT_POST,'password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $passCon=filter_input(INPUT_POST,'password1',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    try {
        if ($pseudo && $mail && $pass && $passCon) {
            // vérifications que les données soient bien valide
            if (!($pass === $passCon)) {
                throw new Exception("Il semblerait y avoit un problème sur la confirmation du mot du passe . . .", 1);
            }
    
            if (strlen($pass)<8) {
                throw new Exception("Il semblerait y avoir un problème sur la taille du mdp", 1);
                
            }
            $pass = password_hash($pass,PASSWORD_DEFAULT);
    
            if ($pseudo == "") {
                throw new Exception("Le pseudo semble etre invalide", 1);
            }
            $request = $pdo->prepare('
                SELECT *
                FROM user
                WHERE mail = :mail
            ');
            $request->bindParam(':mail',$mail);
            $request->execute();
            $verification = $request->fetchAll();
    
            if (!empty($verification)) {
                throw new Exception("Le mot de passe ou le mail semble etre invalide . . .", 1);
            }
            
            $request=$pdo->prepare('
                INSERT INTO user (
                pseudo,
                mail,
                pass)
                VALUES (
                :pseudo,
                :mail,
                :password);
            ');
            $request->bindParam(':password',$pass);
            $request->bindParam(':mail',$mail);
            $request->bindParam(':pseudo',$pseudo);
            $request->execute();
    
            $_SESSION['success']="Félicitation vous vous etes inscrit !"; 
            header('Location:./index.php');
            die;
        }
    } catch (\Exception $e) {
        $_SESSION['error']=$e->getMessage();
        header('Location:./index.php');
        die;
    }

}


if (isset($_POST['connectForm'])) {
    // Dans le cas ou un formulmaire de connection vient d'etre soumit
    $mail=filter_input(INPUT_POST,'mail',FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_VALIDATE_EMAIL);
    $pass=filter_input(INPUT_POST,'password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($mail && $pass) {

        try {
            
            $request=$pdo->prepare('
                SELECT *
                FROM user
                WHERE mail=:mail
            ');
            $request->bindParam(':mail',$mail);
            $request->execute();
            $log=$request->fetch(PDO::FETCH_ASSOC);

            if (empty($log)) {
                throw new Exception("le mail ou le mot de passe semble etre invalide . . .", 1);
            }

            if (!password_verify($pass,$log['pass'])) {
                throw new Exception("le mail ou le mot de passe semble etre invalide . . .", 1);
            }
            
            $_SESSION['user']=$log;
            $_SESSION['success']='Vous vous etes bien connecté';
            
            header('Location:./index.php');
            die;

        } catch (\Exception $e) {
            $_SESSION['error']=$e->getMessage();
            header('Location:./index.php');
            die;
        }

    }
}




?>