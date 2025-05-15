<form method="POST" class="mb-4 p-3 bg-white rounded shadow">
    <h5><?= $editData ? 'Edit' : 'Add' ?> Student</h5>
    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

    <div class="row g-2">
        <div class="col-md-3"><input type="text" name="book_title" class="form-control" placeholder="Book Title"
            required value="<?= $editData['book_title'] ?? '' ?>"></div>
        <div class="col-md-3"><input type="text" name="author_name" class="form-control" placeholder="Author Name"
            required value="<?= $editData['author_name'] ?? '' ?>"></div>
        <div class="col-md-3"><input type="text" name="book_cover" class="form-control" placeholder="Book Cover URL"
            required value="<?= $editData['book_cover'] ?? '' ?>"></div>
        <div class="col-md-2"><input type="text" name="genre" class="form-control" placeholder="Genre"
            required value="<?= $editData['genre'] ?? '' ?>"></div>
        <div class="col-md-2"><input type="number" name="publication_year" class="form-control" placeholder="Publication Year"
            required value="<?= $editData['publication_year'] ?? '' ?>"></div>
        <div class="col-md-2"><input type="number" name="quantity" class="form-control" placeholder="Quantity"
            required value="<?= $editData['quantity'] ?? '' ?>"></div>

    </div>

    <div class="mt-4">
        <button type="submit" name="save" class="btn btn-success "><?= $editData ? 'Update' : 'Save' ?></button>
        <?php if ($editData): ?>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
    </div>
</form>