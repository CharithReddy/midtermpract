drop database if exists books_database;

create database books_database;

use books_database;

create table books(
	bookID int primary key auto_increment,
    bookName VARCHAR(50),
    bookDescription VARCHAR(300),
    stock int,
    price decimal(5, 2),
    genre VARCHAR(10)
);

INSERT INTO books (bookName, bookDescription, stock, price, genre)
VALUES 
('The Great Gatsby', 'A novel set in the Roaring Twenties, following the mysterious Jay Gatsby and his obsession with Daisy Buchanan.', 12, 15.99, 'Fiction'),
('1984', 'A dystopian novel by George Orwell, presenting a terrifying vision of a totalitarian future.', 8, 12.50, 'Sci-Fi'),
('To Kill a bird', 'A classic of modern American literature, exploring racial injustice in the Deep South.', 5, 10.75, 'Fiction'),
('Moby Dick', 'A story about Captain Ahab’s obsessive quest to kill the white whale, Moby Dick.', 4, 17.80, 'Adventure'),
('The Catcher', 'A novel about teenage rebellion and angst, centered around Holden Caulfield.', 9, 14.20, 'Fiction');

select * from books;

