<?php
class Brand {
    private $conn;
    private $table = 'Brands';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name) {
        $query = "INSERT INTO " . $this->table . " (brand_name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        
        return $stmt->execute();
    }
    
}
?>