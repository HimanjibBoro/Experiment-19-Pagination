<?php
require "db.php";

$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$offset = ($page - 1) * $limit;

$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.author_id = users.id
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$countResult = $conn->query("SELECT COUNT(*) AS total FROM posts");
$totalRows = $countResult->fetch_assoc()['total'];

$totalPages = ceil($totalRows / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pagination Example</title>
</head>
<body>

<h2>Blog Posts</h2>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['content'] . "</p>";
        echo "<small>Author: " . $row['username'] . " | Date: " . $row['created_at'] . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>No posts found.</p>";
}
?>

<div style="margin-top:20px;">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>">Previous</a>
    <?php endif; ?>

    &nbsp; Page <?php echo $page; ?> of <?php echo $totalPages; ?> &nbsp;

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>">Next</a>
    <?php endif; ?>
</div>

</body>
</html>
