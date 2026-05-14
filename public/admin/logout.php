<?php

require __DIR__ . '/../../app/bootstrap.php';

logout_admin();
header('Location: index.php');
exit;
