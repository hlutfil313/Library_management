<?php
require_once 'library_db.php';

// Initialize LibraryDB
$libraryDB = new LibraryDB();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // Handle file upload for book cover
        $book_cover = null;
        if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] === UPLOAD_ERR_OK) {
            $book_cover = file_get_contents($_FILES['book_cover']['tmp_name']);
        }

        switch ($action) {
            case 'create':
                $result = $libraryDB->createBook(
                    $_POST['book_title'],
                    $_POST['author_name'],
                    $book_cover,
                    $_POST['genre'],
                    $_POST['publication_year'],
                    $_POST['quantity']
                );
                if ($result) {
                    echo "<p>Book added successfully!</p>";
                } else {
                    echo "<p>Error adding book.</p>";
                }
                break;
                
            case 'update':
                $result = $libraryDB->updateBook(
                    $_POST['id'],
                    $_POST['book_title'],
                    $_POST['author_name'],
                    $book_cover,
                    $_POST['genre'],
                    $_POST['publication_year'],
                    $_POST['quantity']
                );
                if ($result) {
                    echo "<p>Book updated successfully!</p>";
                } else {
                    echo "<p>Error updating book.</p>";
                }
                break;
        }
    }
}

// Handle delete requests
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $result = $libraryDB->deleteBook($_GET['id']);
    if ($result) {
        echo "<p>Book deleted successfully!</p>";
    } else {
        echo "<p>Error deleting book.</p>";
    }
}

// Get all books for display
$books = $libraryDB->getBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { max-width: 100px; max-height: 100px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
    </style>
</head>
<body>
    <h1>Library Management System</h1>
    
    <!-- Add/Edit Book Form -->
    <h2><?php echo isset($_GET['edit']) ? 'Edit Book' : 'Add New Book'; ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>">
        <?php if (isset($_GET['edit'])): ?>
            <input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="book_title">Book Title:</label>
            <input type="text" id="book_title" name="book_title" required 
                   value="<?php echo isset($_GET['edit']) ? $libraryDB->getBook($_GET['edit'])['book_title'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="author_name">Author Name:</label>
            <input type="text" id="author_name" name="author_name" required 
                   value="<?php echo isset($_GET['edit']) ? $libraryDB->getBook($_GET['edit'])['author_name'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="book_cover">Book Cover:</label>
            <input type="file" id="book_cover" name="book_cover" <?php echo isset($_GET['edit']) ? '' : 'required'; ?>>
        </div>
        
        <div class="form-group">
            <label for="genre">Genre:</label>
            <select id="genre" name="genre" required>
                <option value="">Select Genre</option>
                <option value="Fiction" <?php echo (isset($_GET['edit']) && $libraryDB->getBook($_GET['edit'])['genre'] === 'Fiction') ? 'selected' : ''; ?>>Fiction</option>
                <option value="Non-Fiction" <?php echo (isset($_GET['edit']) && $libraryDB->getBook($_GET['edit'])['genre'] === 'Non-Fiction') ? 'selected' : ''; ?>>Non-Fiction</option>
                <option value="Science Fiction" <?php echo (isset($_GET['edit']) && $libraryDB->getBook($_GET['edit'])['genre'] === 'Science Fiction') ? 'selected' : ''; ?>>Science Fiction</option>
                <option value="Fantasy" <?php echo (isset($_GET['edit']) && $libraryDB->getBook($_GET['edit'])['genre'] === 'Fantasy') ? 'selected' : ''; ?>>Fantasy</option>
                <option value="Mystery" <?php echo (isset($_GET['edit']) && $libraryDB->getBook($_GET['edit'])['genre'] === 'Mystery') ? 'selected' : ''; ?>>Mystery</option>
                <option value="Biography" <?php echo (isset($_GET['edit']) && $libraryDB->getBook($_GET['edit'])['genre'] === 'Biography') ? 'selected' : ''; ?>>Biography</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="publication_year">Publication Year:</label>
            <input type="number" id="publication_year" name="publication_year" min="1900" max="<?php echo date('Y'); ?>" required 
                   value="<?php echo isset($_GET['edit']) ? $libraryDB->getBook($_GET['edit'])['publication_year'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required 
                   value="<?php echo isset($_GET['edit']) ? $libraryDB->getBook($_GET['edit'])['quantity'] : '1'; ?>">
        </div>
        
        <button type="submit"><?php echo isset($_GET['edit']) ? 'Update Book' : 'Add Book'; ?></button>
        <?php if (isset($_GET['edit'])): ?>
            <a href="librarys.php">Cancel</a>
        <?php endif; ?>
    </form>
    
    <!-- Books Listing -->
    <h2>Book List</h2>
    <?php if (count($books) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Cover</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author_name']); ?></td>
                        <td>
                            <?php if (!empty($book['book_cover'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($book['book_cover']); ?>" alt="Book Cover">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($book['genre']); ?></td>
                        <td><?php echo $book['publication_year']; ?></td>
                        <td><?php echo $book['quantity']; ?></td>
                        <td>
                            <a href="librarys.php?edit=<?php echo $book['id']; ?>">Edit</a> | 
                            <a href="librarys.php?action=delete&id=<?php echo $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No books found in the library.</p>
    <?php endif; ?>
</body>
</html>