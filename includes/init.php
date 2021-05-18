<?php
// open connection to database
include_once("includes/db.php");
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');

// // check login/logout params
include_once("includes/sessions.php");
$session_messages = array();
process_session_params($db, $session_messages);

// is the current user a property manager?
define('MANAGER_GROUP_ID', 1);
$is_manager = is_user_member_of($db, MANAGER_GROUP_ID);

// is the current user an admin?
define('ADMIN_GROUP_ID', 3);
$is_admin = is_user_member_of($db, ADMIN_GROUP_ID);
