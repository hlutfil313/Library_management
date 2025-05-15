<?php
// Example controller logic for students.php
include 'config.php';

$viewStudent = null;
if (isset($_GET['view'])) {
    $viewId = intval($_GET['view']);
    $viewStudent = $conn->query("SELECT * FROM students WHERE id=$viewId")->fetch_assoc();
}

include 'views/header.php'; ?>

<?php if (!$viewStudent): ?>
  <div class="alert alert-danger">Student not found.</div>
<?php else: ?>
  <div class="card shadow p-4">
    <h4 class="mb-3">Student Details</h4>
    <ul class="list-group">
      <li class="list-group-item"><strong>ID:</strong> <?= $viewStudent['id'] ?></li>
    <li class="list-group-item"><strong>Book Title:</strong> <?= $viewStudent['book_title'] ?></li>
    <li class="list-group-item"><strong>Author Name:</strong> <?= $viewStudent['author_name'] ?></li>
    <li class="list-group-item"><strong>Book Cover:</strong> <img src="<?= $viewStudent['book_cover'] ?>" alt="Book Cover" style="max-width: 100px;"></li>
    <li class="list-group-item"><strong>Genre:</strong> <?= $viewStudent['genre'] ?></li>
    <li class="list-group-item"><strong>Publication Year:</strong> <?= $viewStudent['publication_year'] ?></li>
    <li class="list-group-item"><strong>Quantity:</strong> <?= $viewStudent['quantity'] ?></li>
    </ul>
    <a href="students.php" class="btn btn-primary mt-3">Back to List</a>
  </div>
<?php endif; ?>