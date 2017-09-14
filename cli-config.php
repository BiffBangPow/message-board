<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once "services.php";

return ConsoleRunner::createHelperSet($entityManager);