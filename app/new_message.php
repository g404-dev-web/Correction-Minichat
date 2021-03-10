<?php
require_once './dbconnexion.php';
if (isset($_POST['pseudo']) && 
    isset($_POST['message']) && 
    !empty($_POST['message']) && 
    !empty($_POST['pseudo'])) {


        $message = $_POST['message'];
        $pseudo = $_POST['pseudo'];


        $getUserStatement = $pdo->prepare("SELECT * FROM users WHERE nickname = ?");
        $getUserStatement->execute([
            $pseudo
        ]);
        $user = $getUserStatement->fetch(PDO::FETCH_ASSOC);
        // Si je récupère mon user 
        function getIp(){
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
        function insertMessage($pdo, $message, $user_id){
            $newMessageStatement = $pdo->prepare("INSERT INTO messages (user_id, message) VALUE (?,?)");
            $newMessageStatement->execute([
                $user_id,
                $message
            ]);
        }
        if ($user){

            // J'envoi un message en utilisant l'id du user
            insertMessage($pdo,$message, $user['id']);
        }else{
            //Sinon j'enregistre en bdd un nouvel utilisateur
                // je recupère son id et j'envoi le message
            $newUserStatement = $pdo->prepare("INSERT INTO users (nickname, ip_address) VALUE (?,?)");
            $newUserStatement->execute([
                $pseudo,
                getIp()
            ]);

            $last_user_id = $pdo->lastInsertId();

            insertMessage($pdo,$message, $last_user_id);
        }   

}