<?php
/*
 * Developer 7: Alex (Logout & Security)
 * - Handles user logout and session destruction
 * - Clears session data to ensure user is logged out
 * - Implements basic CSRF protection
 * - TODO: Implement token-based logout for additional security
 */

 session_start();
 session_unset();
 session_destroy();
 header('Location: login.php');
 exit();
 ?>
 
