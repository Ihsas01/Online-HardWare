<?php
require_once 'php/config.php';

// Destroy the session
session_start();
session_destroy();

// Clear any session cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to home page
redirect('index.php');
?> 