<?php
// test_connection.php

include_once 'db.php';

$database = new Database();

$db = $database->getConnection();

if ($db) {
    echo "<h2>Database Connection Successful!</h2>";
    echo "<p>Connected to database: Phonify</p>";
    
    try {
        $stmt = $db->query("SELECT title FROM Products LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>First title present in database: " . $row['title'] . "</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>Could not run test query. Ensure tables exist.</p>";
    }

} else {
    echo "<h2>❌ Database Connection Failed.</h2>";
}

$db = null; 
?>