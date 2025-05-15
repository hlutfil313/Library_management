<?php
require_once 'config.php';
include 'config.php';

// Insert or Update Book
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $book = $_POST['book_title'];
    $author = $_POST['author_name'];
    $bookc = $_POST['book_cover'];
    $genre = $_POST['genre'];
    $publication = $_POST['publication_year'];
    $quantity = $_POST['quantity'];

    if ($id) {
        // Update existing book
        $conn->query("UPDATE books SET book_title='$book', author_name='$author', book_cover='$bookc', genre='$genre', publication_year='$publication', quantity='$quantity' WHERE id=$id");
    } else {
        // Insert new book
        $conn->query("INSERT INTO books (book_title, author_name, book_cover, genre, publication_year, quantity) VALUES ('$book', '$author', '$bookc', '$genre', '$publication', '$quantity')");
    }
    header("Location: index.php");
    exit();
}

// Fetch all books
$result = $conn->query("SELECT * FROM library");

// Get book for edit (if any)
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = $conn->query("SELECT * FROM library WHERE id=$id")->fetch_assoc();
}

// Fetch book for view
if (isset($_GET['view'])) {
    $viewId = intval($_GET['view']);
    $viewBook = $conn->query("SELECT * FROM library WHERE id=$viewId")->fetch_assoc();
    include 'views/library.php';
    exit();
}

// Delete book
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM library WHERE id=$id");
    header("Location: index.php");
    exit();
}

include 'view/header.php';
include 'view/form.php';
include 'view/library_table.php';



?>