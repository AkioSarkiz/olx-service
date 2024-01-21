<?php
/**
 * This is only testing example, you should use queue, jobs, proxy, etc for production.
 */

require __DIR__ . '/../db.php';

// You should use chunks for it, but it's not necessary now.
$stmt = $mysqli->prepare("SELECT `id`, `url`, `price` FROM `ads` LIMIT 10000");
$stmt->execute();

$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->begin_transaction();

try {
    foreach ($records as $record) {
        $command = escapeshellcmd(__DIR__ . '/../bin/parse_olx.py ' . $record['url']);
        $price = trim(shell_exec($command));
    
        if ($record['price'] == $price) {
            continue;
        }
        
        $stmt = $mysqli->prepare('UPDATE `ads` SET `price` = ? WHERE `id` = ?');
        $stmt->bind_param('si', $price, $record['id']);
        $stmt->execute();


        $message = "Price has changed from {$record['price']} to $price";
        $headers = "From: noreploy@service.com";

        // again, chunk should be here.
        $stmt = $mysqli->prepare(<<<SQL
SELECT `id`, `email`
FROM `users`
INNER JOIN `ad_on_user` ON `ad_on_user`.`user_id` = `users`.`id`
WHERE `ad_on_user`.`ad_id` = ?;
SQL);
        $stmt->bind_param('i', $record['id']);
        $stmt->execute();

        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($users as $user) {
            // send test email, better to use phpmail library here.
            mail($user['email'], "test", $message, $headers);
        }
    }
    
    $mysqli->commit();
} catch (Error $e) {
    var_dump($e);
    $mysqli->rollback();
} finally {
    $stmt->close();
    $mysqli->close();
}
