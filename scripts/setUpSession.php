<?php
// server should keep session data for AT LEAST 1 month
ini_set('session.gc_maxlifetime', 2592000);

// each client should remember their session id for EXACTLY 1 month
session_set_cookie_params(2592000);

// Start the session
session_start();

