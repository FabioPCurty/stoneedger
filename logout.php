<?php
require_once 'api/session_handler.php';
session_destroy();
header('Location: index.php');
exit;
