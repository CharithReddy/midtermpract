<?php
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'password');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'books_database');



    class DatabaseConnection {
        const DB_USER = 'root';
		const DB_PASSWORD = 'password';
		const DB_HOST = 'localhost';
		const DB_NAME = 'books_database';

        private $dbc;

        function __construct() {
            $this->dbc = @mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME)
            OR die('Could not connect to MySQL: ' . mysqli_connect_error());
            mysqli_set_charset($this->dbc, 'utf8');
        }

        function prepare_string($string) {
            $string = strip_tags($string);
            $string = mysqli_real_escape_string($this->dbc, trim($string));
            return $string;
        }

        function get_dbc() {
            return $this->dbc;
        }

        function register_book($book_name, $book_desc, $book_quantity, 
                    $book_price, $book_genre) {
            
            $book_name = $this->prepare_string($book_name);
            $book_desc = $this->prepare_string($book_desc);
            $book_genre = $this->prepare_string($book_genre);

            $query = "INSERT INTO `books`(`bookName`, `bookDescription`, `stock`,
                        `price`, `genre`) VALUES(?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($this->dbc, $query);
            mysqli_stmt_bind_param(
                $stmt,
                "ssids",
                $book_name, 
                $book_desc, 
                $book_quantity, 
                $book_price, 
                $book_genre
            );

            $result = mysqli_stmt_execute($stmt);
            return $result;
        }

        function update_book($book_id, $book_name, $book_desc, $book_quantity, 
            $book_price, $book_genre) {

            $book_id = $this->prepare_string($book_id);
            $book_name = $this->prepare_string($book_name);
            $book_desc = $this->prepare_string($book_desc);
            $book_price = $this->prepare_string($book_price);
            $book_genre = $this->prepare_string($book_genre);

            $query = "UPDATE `books`
                        SET `bookName` = ?, `bookDescription` = ?, 
                            `stock` = ?, `price` = ?, 
                            `genre` = ?
                        WHERE `bookID` = ?";

            $stmt = mysqli_prepare($this->dbc, $query);
            mysqli_stmt_bind_param(
                $stmt,
                'ssidsi',
                $book_name,
                $book_desc,
                $book_quantity,
                $book_price,
                $book_genre,
                $book_id
            );

            $result = mysqli_stmt_execute($stmt);

            return $result;
        }

        function delete_book($book_id) {
            $book_id = $this->prepare_string($book_id);
            $query = "delete from books where `bookID` = $book_id";
            $result = mysqli_query($this->dbc, $query);

            return $result;
        }

        function get_all_books() {
            $query = 'SELECT * FROM books;'; 
            $results = mysqli_query($this->dbc, $query);

            return $results;
        }
    }

    class ValidationFunctions {

        // Function to validate book name ex John, John Doe
        static function validate_book($value) {
            $value = trim($value);

            if (preg_match('/[a-zA-Z]+( [A-Za-z\d]+)?/', $value)) {
                return true;
            } else {
                return false;
            }
        }
    
        static function validate_numbers($value) {
            $value = trim($value);
    
            if (preg_match('/^[\d]+$/', $value)) {
                return true;
            } else {
                return false;
            }
        }
    
        // validate price 
        static function validate_price($value) {
            $value = trim($value);
    
            if (preg_match('/^([1-9][\d]{1}|[\d]{1,3})(\.[\d]{1,2})?$/', $value)) {
                return true;
            } else {
                return false;
            }
        }
    
    }
?>

