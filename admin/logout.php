<?php
/**
 * Admin Logout Handler
 */
require_once __DIR__ . '/auth.php';

logoutAdmin();

header('Location: login.php?msg=logged_out');
exit;
