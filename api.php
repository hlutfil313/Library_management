<?php
// Include database configuration
require_once 'config.php';

// Set response header to JSON
header('Content-Type: application/json');

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle CORS (Cross-Origin Resource Sharing)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// If it's an OPTIONS request, end response
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the endpoint from URL
$request_uri = $_SERVER['REQUEST_URI'];
$uri_parts = explode('/', $request_uri);
$endpoint = end($uri_parts);

// Process based on HTTP method and endpoint
switch ($method) {
    case 'GET':
        // Read operation
        if ($endpoint == 'books' || $endpoint == 'api.php') {
            // Get all books
            $sql = "SELECT * FROM library";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $books = array();
                while ($row = $result->fetch_assoc()) {
                    // For book_cover, create a base64 string to display the image
                    if (isset($row['book_cover']) && $row['book_cover'] !== null) {
                        $row['book_cover'] = base64_encode($row['book_cover']);
                    }
                    $books[] = $row;
                }
                echo json_encode(['status' => 'success', 'data' => $books]);
            } else {
                echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No books found']);
            }
        } elseif (is_numeric($endpoint)) {
            // Get a specific book by ID
            $id = $endpoint;
            $sql = "SELECT * FROM library WHERE id = $id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $book = $result->fetch_assoc();
                if (isset($book['book_cover']) && $book['book_cover'] !== null) {
                    $book['book_cover'] = base64_encode($book['book_cover']);
                }
                echo json_encode(['status' => 'success', 'data' => $book]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Book not found']);
            }
        }
        break;

    case 'POST':
        // Create operation
        if ($endpoint == 'books' || $endpoint == 'api.php') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // If data is not in JSON format (likely form data)
            if (!$data && isset($_POST['title'])) {
                $data = $_POST;
            }
            
            // Process the book cover image if it exists
            $bookCover = null;
            if (isset($_FILES['book_cover']) && $_FILES['book_cover']['size'] > 0) {
                $bookCover = file_get_contents($_FILES['book_cover']['tmp_name']);
            }
            
            // Validate required fields
            if (!isset($data['title']) || !isset($data['author_name']) || !isset($data['genre']) || 
                !isset($data['publication_year']) || !isset($data['quantity'])) {
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                break;
            }
            
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO library (title, author_name, book_cover, genre, publication_year, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $data['title'], $data['author_name'], $bookCover, $data['genre'], $data['publication_year'], $data['quantity']);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Book added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error adding book: ' . $stmt->error]);
            }
            
            $stmt->close();
        }
        break;

    case 'PUT':
        // Update operation
        if (is_numeric($endpoint)) {
            $id = $endpoint;
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Build SET clause for SQL query
            $set_clause = [];
            $types = "";
            $params = [];
            
            if (isset($data['title'])) {
                $set_clause[] = "title = ?";
                $types .= "s";
                $params[] = $data['title'];
            }
            
            if (isset($data['author_name'])) {
                $set_clause[] = "author_name = ?";
                $types .= "s";
                $params[] = $data['author_name'];
            }
            
            if (isset($data['genre'])) {
                $set_clause[] = "genre = ?";
                $types .= "s";
                $params[] = $data['genre'];
            }
            
            if (isset($data['publication_year'])) {
                $set_clause[] = "publication_year = ?";
                $types .= "s";
                $params[] = $data['publication_year'];
            }
            
            if (isset($data['quantity'])) {
                $set_clause[] = "quantity = ?";
                $types .= "i";
                $params[] = $data['quantity'];
            }
            
            // If no fields to update
            if (empty($set_clause)) {
                echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
                break;
            }
            
            // Add ID to params for WHERE clause
            $types .= "i";
            $params[] = $id;
            
            // Prepare SQL statement
            $sql = "UPDATE library SET " . implode(", ", $set_clause) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            // Dynamically bind parameters
            $bind_params = array_merge([$types], $params);
            $stmt->bind_param(...$bind_params);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Book updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating book: ' . $stmt->error]);
            }
            
            $stmt->close();
        }
        break;

    case 'DELETE':
        // Delete operation
        if (is_numeric($endpoint)) {
            $id = $endpoint;
            $sql = "DELETE FROM library WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Book deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error deleting book: ' . $stmt->error]);
            }
            
            $stmt->close();
        }
        break;

    default:
        // Invalid method
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        break;
}

// Close database connection
$conn->close();
?>