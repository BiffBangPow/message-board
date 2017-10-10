<?php

use BiffBangPow\MessageBoard\Model\Thread;
use BiffBangPow\MessageBoard\Model\Comment;
use BiffBangPow\MessageBoard\Model\User;
use \BiffBangPow\MessageBoard\Services\PasswordEncryptionService;

require_once __DIR__ . "/../services.php";

// Truncate all Tables https://jamesmcfadden.co.uk/truncating-all-tables-in-doctrine
$connection = $entityManager->getConnection();
$schemaManager = $connection->getSchemaManager();
$tables = $schemaManager->listTables();
$query = '';
foreach($tables as $table) {
    $name = $table->getName();
    $query .= 'TRUNCATE ' . $name . ';';
}
$connection->executeQuery($query, array(), array());

// Add some dummy data
$passwordEncryptionService = new PasswordEncryptionService();

$threadContent = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Ut posuere sapien ut tellus cursus, ut auctor urna malesuada. 
Mauris pretium ut nisl at vulputate. Duis varius elementum tortor, rutrum porta velit vehicula eu. 
Sed porttitor molestie porttitor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos 
himenaeos. Integer et porttitor erat. Fusce pharetra arcu nec bibendum aliquam. Praesent eleifend ipsum lacinia, ornare 
quam id, dignissim turpis. Vivamus velit ligula, maximus placerat hendrerit quis, molestie ac felis. Ut ligula arcu, 
laoreet vel elementum ac, varius id ligula.
EOT;

$user = new User();
$user ->setUsername('Testuser');
$user = $passwordEncryptionService->encryptPassword($user, 'testpassword');
$entityManager->persist($user);

for ($t = 1; $t<=20; $t++) {
    $thread = new Thread();
    $thread->setTitle(sprintf("Thread %d", $t));
    $thread->setContent($threadContent);
    $thread->setUser($user);
    $entityManager->persist($thread);

    for ($c = 1; $c<=20; $c++) {
        $comment = new Comment();
        $comment->setThread($thread);
        $comment->setUser($user);
        $comment->setContent($c.'A Test Comment');
        $entityManager->persist($comment);
    }
}
$entityManager->flush();
