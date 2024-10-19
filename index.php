<?php
    require('dbinit.php');
    $connection = new DatabaseConnection();
    $results = $connection->get_all_books();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>books List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" 
        crossorigin="anonymous">
    <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">

</head>
<body>

    <?php

        // Do not do the validation if the page is loaded first
        // validation is done only if submit button is clicked
        if (!empty($_POST)) {

            // Do the data validation here
            $book_id = $_POST['cID'];
            $book_name = $_POST['cName'];
            $book_desc = $_POST['cDesc'];
            $book_quantity = $_POST['cQuantity'];
            $book_price = $_POST['cPrice'];
            $book_genre = $_POST['cGenre'];

            // Define an errors array
            $errors = [];

            // Validate if book name is empty or if it has alphabets only
            if (empty($book_name)) {
                $errors['book_name'] = "Book Name cannot be empty";
            } else if (!ValidationFunctions::validate_book($book_name)){
                $errors['book_name'] = "Book Name must contain only letters.";
            }

            // Validate if book description is empty or if it has alphabets only
            if (empty($book_desc)) {
                $errors['book_desc'] = "Book Description cannot be empty";
            }

            // Validate if book quantity is empty or if it has alphabets only
            if (empty($book_quantity)) {
                $errors['book_quantity'] = "Book Quantity cannot be empty";
            } else if (!ValidationFunctions::validate_numbers($book_quantity)){
                $errors['book_quantity'] = "Book Quantity must contain only digits.";
            }

            // Validate if book price is empty or if it has alphabets only
            if (empty($book_price)) {
                $errors['book_price'] = "book Price cannot be empty";
            } 
            else if (!ValidationFunctions::validate_price($book_price)){
                $errors['book_price'] = "book Price must be between 100 and 999.99.";
            }

            if (empty($book_genre)) {
                $errors['book_genre'] = 'Please select a book genre.';
            }

            if (!empty($errors)) {
                // If there are errors save it session
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
            } else {
                // If there are no errors then unset the session
                unset($_SESSION['errors']);
                unset($_SESSION['form_data']);
                
                $result = null;

                // If book_id is empty it means that we are inserting into the table
                if (empty($book_id)) {
                    $result = $connection->register_book($book_name, $book_desc, $book_quantity, 
                                $book_price, $book_genre); 
                } else {

                    $result = $connection->update_book($book_id, $book_name, $book_desc, $book_quantity,
                                $book_price, $book_genre);
                }

                // If successfull then refresh the page to show new data.
                if ($result) {
                    header('Location: index.php');
                } else {
                    echo "Something went wrong. Inserting or Updateing book data failed.";
                }

            }

        }

        // This block will be run to delete data from the table
        if (!empty($_GET['delete_cid'])) {
            $book_to_delete = $_GET['delete_cid'];
            $result = $connection->delete_book($book_to_delete);

            // Refresh the page if successfull.
            if ($result) {
                header('Location: index.php');
            } else {
                echo "Something went wrong. Unable to delete from books.";
            }

        }

        // Use the session data to prefill the form
        $form_data = $_SESSION['form_data'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
    ?>

    <div class="table-container">

        <div id="show-book-data">
            <div class="flexrow">
                <h1>Please see the list of all books.</h1>
                <a href="#add-edit-book-data">
                    <button id="add-book" class="btn btn-primary">+1 Add New book</button></a>
            </div>

            <!-- Table to display each row in the books table -->
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">book ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Edit/Delete</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        $sr_no = 0;
                        while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
                            $sr_no++;
                            $str_to_print = "";
                            $str_to_print = "<tr>";
                            $str_to_print .= "<th scope=\"row\" id=\"cid-{$row['bookID']}\">{$row['bookID']}</th>";
                            $str_to_print .= "<td id=\"cname-{$row['bookID']}\">{$row['bookName']}</td>";
                            $str_to_print .= "<td id=\"cdesc-{$row['bookID']}\">{$row['bookDescription']}</td>";
                            $str_to_print .= "<td id=\"cprice-{$row['bookID']}\">{$row['price']}</td>";
                            $str_to_print .= "<td id=\"cgenre-{$row['bookID']}\">{$row['genre']}</td>";
                            $str_to_print .= "<td id=\"cstock-{$row['bookID']}\">{$row['stock']}</td>";
                            $str_to_print .= "<td>
                                                <a href=\"#add-edit-book-data\"><button id=\"edit-book-{$row['bookID']}\" class=\"edit-book\"><i class=\"fa-regular fa-pen-to-square fa-2x\"></i></button></a> 
                                                    |
                                                <a href=\"#delete-book-data\"><button id=\"delete-book-{$row['bookID']}\" class=\"delete-book\"><i class=\"fa-solid fa-trash-arrow-up fa-2x\"></i></button></a>     
                                              </td>";
                            $str_to_print .= "</tr>";

                            echo $str_to_print;
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- The heading in this div depends on the operation -->
        <div id="add-edit-book-data">
            <form name="add_edit_book" class="needs-validation"
                method="post" action="index.php" novalidate>

                <h1 class="text-center" id="add-edit-form-heading">
                    Please fill the form to add new book
                </h1>

                <!-- This field is hidden. It is used to determine to add or update the data in table -->
                <p class="hidden">
                    <label for="cID">book ID:  </label>
                    <input type="text" name="cID" id="cID" 
                        class="form-control"
                        value="<?= isset($form_data['cID']) ? $form_data['cID'] : "" ?>" readonly>
                </p>

                <p> 
                    <label for="cName">book Name:  </label>
                    <input type="text" name="cName" id="cName" 
                        class="form-control <?= isset($errors['book_name']) ? "invalidval" : "" ?>"
                        placeholder="Please enter your first name" 
                        value="<?= isset($form_data['cName']) ? $form_data['cName'] : "" ?>">
                    <span class="errortext"><?= isset($errors['book_name']) ? $errors['book_name'] : "" ?></span>
                </p>

                <p> 
                    <label for="cDesc">book Description:  </label>
                    <textarea name="cDesc" id="cDesc" 
                        class="form-control <?= isset($errors['book_desc']) ? "invalidval" : "" ?>"
                        placeholder="Please enter the description of book"
                        value="<?= isset($form_data['cDesc']) ? $form_data['cDesc'] : "" ?>"></textarea>
                    <span class="errortext"><?= isset($errors['book_desc']) ? $errors['book_desc'] : "" ?></span>
                </p>

                <p> 
                    <label for="cQuantity">book Quantity:  </label>
                    <input type="text" name="cQuantity" id="cQuantity" 
                        class="form-control <?= isset($errors['book_quantity']) ? "invalidval" : "" ?>"
                        placeholder="Please enter stock available" 
                        value="<?= isset($form_data['cQuantity']) ? $form_data['cQuantity'] : "" ?>">
                    <span class="errortext"><?= isset($errors['book_quantity']) ? $errors['book_quantity'] : "" ?></span>
                </p>

                <p> 
                    <label for="cPrice">book Price:  </label>
                    <input type="text" name="cPrice" id="cPrice" 
                        class="form-control <?= isset($errors['book_price']) ? "invalidval" : "" ?>"
                        placeholder="Please the price of the book" 
                        value="<?= isset($form_data['cPrice']) ? $form_data['cPrice'] : "" ?>">
                    <span class="errortext"><?= isset($errors['book_price']) ? $errors['book_price'] : "" ?></span>
                </p>

                <p> 
                    <label for="cGenre">book Genre:  </label>
                    <select name="cGenre" id="cGenre"
                        class="form-select <?= isset($errors['book_genre']) ? "invalidval" : "" ?>">
                        <option value=""
                            <?= isset($form_data['cGenre']) && $form_data['cGenre'] == "" ? "selected" : "" ?>>
                            Select One</option>
                        <option value="Sci-Fi"
                            <?= isset($form_data['cGenre']) && $form_data['cGenre'] == "Sci-Fi" ? "selected" : "" ?>>
                            Sci-Fi</option>
                        <option value="Fiction"
                            <?= isset($form_data['cGenre']) && $form_data['cGenre'] == "Fiction" ? "selected" : "" ?>>
                            Fiction</option>
                        <option value="Adventure"
                            <?= isset($form_data['cGenre']) && $form_data['cGenre'] == "Adventure" ? "selected" : "" ?>>
                            Adventure</option>
                    </select>
                    <span class="errortext"><?= isset($errors['book_genre']) ? $errors['book_genre'] : "" ?></span>
                </p>

                <p class="btn-row">
                    <input type="submit" id="submit" value="Submit" class="btn btn-primary submitbtn"/>
                    <input type="reset" id="cancel-add" value="Cancel" class="btn btn-danger submitbtn"/>
                </p>

            </form>
        </div>

        <!-- This div is show if delete button is clicked otherwise it is hidden -->
        <div id="delete-book-data" class="hidden">
            <form name="delete_book" class="needs-validation"
                action="index.php" novalidate>
                <h1 class="text-center" id="delete-form-heading">
                    Are you sure you want to delete book id <span id="delete-book-id-span"></span>
                </h1>
                
                <p class="hidden">
                    <label for="delete_cid">book ID:  </label>
                    <input type="text" name="delete_cid" id="delete_cid" class="form-control" value="">
                </p>

                <p class="btn-row">
                    <input type="submit" id="submit-delete" value="Submit" class="btn btn-primary submitbtn"/>
                    <input type="reset" id="cancel-delete" value="Cancel" class="btn btn-danger submitbtn"/>
                </p>

            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>

</body>
</html>

