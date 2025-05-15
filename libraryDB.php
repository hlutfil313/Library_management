<?php
class LibraryDB {
    private $host = 'localhost';
    private $db_name = 'library';
    private $username = 'root'; // Change to your DB username
    private $password = ''; // Change to your DB password
    private $conn;

    // Database connection
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }

    // Create a new book
    public function createBook($title, $author, $book_cover, $genre, $publication_year, $quantity) {
        $sql = "INSERT INTO library (book_title, author_name, book_cover, genre, publication_year, quantity) 
                VALUES (:title, :author, :book_cover, :genre, :publication_year, :quantity)";
        
        $stmt = $this->connect()->prepare($sql);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':book_cover', $book_cover, PDO::PARAM_LOB);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':publication_year', $publication_year);
        $stmt->bindParam(':quantity', $quantity);
        
        return $stmt->execute();
    }

    // Get all books
    public function getBooks() {
        $sql = "SELECT * FROM library";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single book by ID
    public function getBook($id) {
        $sql = "SELECT * FROM library WHERE id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a book
    public function updateBook($id, $title, $author, $book_cover, $genre, $publication_year, $quantity) {
        $sql = "UPDATE library SET 
                book_title = :title, 
                author_name = :author, 
                book_cover = :book_cover, 
                genre = :genre, 
                publication_year = :publication_year, 
                quantity = :quantity 
                WHERE id = :id";
        
        $stmt = $this->connect()->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':book_cover', $book_cover, PDO::PARAM_LOB);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':publication_year', $publication_year);
        $stmt->bindParam(':quantity', $quantity);
        
        return $stmt->execute();
    }

    // Delete a book
    public function deleteBook($id) {
        $sql = "DELETE FROM library WHERE id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>