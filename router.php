<?php
function check_php_version()
{
  if (version_compare(phpversion(), '7.0', '<')) {
    define('VERSION_MESSAGE', "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
    echo VERSION_MESSAGE;
    throw VERSION_MESSAGE;
  }
}

function config_php_errors()
{
  // error reporting the same for all students
  ini_set('error_log', NULL);
  ini_set('log_errors', true);
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}

function default_php_settings()
{
  ini_set('short_open_tag', 'Off'); // No short tags
}

// check current php version to ensure it meets 2300's requirements
check_php_version();
config_php_errors();
default_php_settings();

function match_routes($uri, $routes)
{
  if (is_array($routes)) {
    foreach ($routes as $route) {
      if (($uri == $route) || ($uri == $route . '/')) {
        return True;
      }
    }
    return False;
  } else {
    return ($uri == $routes) || ($uri == $routes . '/');
  }
}

// Grabs the URI and breaks it apart in case we have querystring stuff
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
error_log('routing: ' . $request_uri);

if (preg_match('/^\/public\//', $request_uri)) {
  // serve the requested resource as-is.
  return False;
} else if (match_routes($request_uri, ['/','/listings','/index','/home'])) {
  require 'pages/home.php';
} else if (match_routes($request_uri, ['/add-listing', '/add', '/create-listing', '/create'])){
  require 'pages/add-listing.php';
} else if (match_routes($request_uri, ['/edit-listing', '/edit'])) {
  require 'pages/edit-listing.php';
} else if (match_routes($request_uri, ['/detail', '/item'])) {
  require 'pages/detail.php';
} else {
  error_log("404 Not Found: " . $request_uri);
  http_response_code(404);
  require 'pages/404.php';
}
