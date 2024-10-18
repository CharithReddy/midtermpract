<?php
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'password');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'charith_reddy_262_cars');

    $dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        OR die('Could not connect to MySQL: ' . mysqli_connect_error());
    mysqli_set_charset($dbc, 'utf8');

    function prepare_string($dbc, $string) {
        $string_trimmed = trim($string);
        $string = mysqli_real_escape_string($dbc, $string_trimmed);
        return $string;
    }
?>

