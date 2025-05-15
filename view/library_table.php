<table class="table table-bordered table-striped bg-white shadow">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Book_title</th>
            <th>Author_name</th>
            <th>Book_cover</th>
            <th>Genre</th>
            <th>Publication_year</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td><?= $row['age'] ?></td>
                <td><?= $row['class'] ?></td>
                <td><?= $row['guardian'] ?></td>

                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this student?')"
                        class="btn btn-sm btn-danger">Delete</a>
                    <a href="?view=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                </td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>