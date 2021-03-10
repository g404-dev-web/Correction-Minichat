<?php

require_once './dbconnexion.php';


$allMessagesStatement = $pdo->prepare("SELECT messages.*, users.nickname FROM messages 
                                        JOIN users 
                                            ON messages.user_id = users.id ORDER BY messages.created_at ASC");

$allMessagesStatement->execute();

$allMessages = $allMessagesStatement->fetchAll(PDO::FETCH_ASSOC);



foreach ($allMessages as $message) { ?>

<div class="row">
    <div class="col s9"><?=$message['message']?></div>
    <div class="col s3"><?= $message['nickname']?> : <?=$message['created_at']?></div>
</div>

<?php } ?>

