<?php

use BiffBangPow\MessageBoard\Model\Thread;

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

// Add some dummy threads
$threadContent = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut posuere sapien ut tellus cursus, ut auctor urna malesuada. Mauris pretium ut nisl at vulputate. Duis varius elementum tortor, rutrum porta velit vehicula eu. Sed porttitor molestie porttitor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer et porttitor erat. Fusce pharetra arcu nec bibendum aliquam. Praesent eleifend ipsum lacinia, ornare quam id, dignissim turpis. Vivamus velit ligula, maximus placerat hendrerit quis, molestie ac felis. Ut ligula arcu, laoreet vel elementum ac, varius id ligula.

Nam in justo finibus felis tristique dapibus vel gravida orci. Nunc non leo orci. Suspendisse ac orci sagittis, ultricies orci sed, eleifend metus. Integer sagittis nulla odio, ac sodales nisi pharetra ac. Sed sed viverra neque. Duis vitae risus ac sem dignissim mollis. Aliquam eget auctor felis, sed scelerisque neque. Proin consequat felis vitae sem facilisis hendrerit. Curabitur nec magna purus. Pellentesque non nisi at nisl gravida sodales a quis augue. Vivamus nec lectus ligula. Integer ornare tellus eget enim gravida, vitae blandit urna interdum. Quisque tempus urna eget diam fringilla ultricies vel ac magna. Praesent quis feugiat leo, et malesuada libero.
EOT;
for ($i = 1; $i<=20; $i++) {
    $thread = new Thread();
    $thread->setTitle(sprintf("Thread %d", $i));
    $thread->setContent($threadContent);
    $entityManager->persist($thread);
}

$entityManager->flush();