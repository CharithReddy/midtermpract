$(document).ready(() => {

    // If cID is empty then form is to add new book, If not empty then it is to edit data
    if ($("#cID").val() === "") {
        $("#add-edit-form-heading").text("Please fill the form to add new book");
    } else {
        $("#add-edit-form-heading").text("Please fill the form to edit book id " + $("#cID").val());
    }

    // While adding the new record to table, do not show the delete-book-data div
    // reset the input fields to have empty values
    $("#add-book").click(() => {
        
        $("#add-edit-form-heading").text("Please fill the form to add new book");
        $("#delete-book-data").addClass("hidden");
        $("#add-edit-book-data").removeClass("hidden");

        $("#cID").val("");
        $("#cName").val("");
        $("#cDesc").val("");
        $("#cPrice").val("");
        $("#cGenre").val("");
        $("#cQuantity").val("");

    });

    // If cancel is clicked while adding or editing, then clear the form and show add book form
    $("#cancel-add").click((evt) => {
        evt.preventDefault();

        $("#add-edit-form-heading").text("Please fill the form to add new book");

        $("#cID").val("");
        $("#cName").val("");
        $("#cDesc").val("");
        $("#cPrice").val("");
        $("#cGenre").val("");
        $("#cQuantity").val("");

    });

    // If a button with edit-book class is clicked, then get the book_id from the button clicked
    // and fill the input fields with values from the relevant row as initial values.
    // do not show the delete book div
    $(".edit-book").click((evt) => {
        bookid = $(evt.currentTarget).attr('id').split("-")[2];

        $("#add-edit-book-data").removeClass("hidden");
        $("#delete-book-data").addClass("hidden");
        $("#add-edit-form-heading").text("Please fill the form to edit book id " + bookid);

        $("#cID").val(bookid);
        $("#cName").val($("#cname-"+bookid).text());
        $("#cDesc").val($("#cdesc-"+bookid).text());
        $("#cPrice").val($("#cprice-"+bookid).text());
        $("#cGenre").val($("#cgenre-"+bookid).text());
        $("#cQuantity").val($("#cstock-"+bookid).text());
    });

    // When a button with delete-book class is clicked. Get the id of the book from the button
    // set the text of the span and hidden input field to book_id
    $(".delete-book").click((evt) => {
        bookid = $(evt.currentTarget).attr('id').split("-")[2];
        console.log("clicked " + bookid);

        $("#delete-book-data").removeClass("hidden");
        $("#add-edit-book-data").addClass("hidden");

        $("#delete_cid").val(bookid);
        $("#delete-book-id-span").text(bookid);
    });

    // Id delete is cancelled then show the add/edit book data form.
    $("#cancel-delete").click((evt) => {
        $("#delete-book-data").addClass("hidden");
        $("#add-edit-book-data").removeClass("hidden");
    });

});

