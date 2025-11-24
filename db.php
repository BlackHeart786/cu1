<?php
// db.php - Database Connection Configuration

class Database {
    private $host = 'localhost'; 
    
    private $db_name = 'phonify'; 
    
    private $username = 'root';        
    
    private $password = '';            
    private $conn;

   
    public function getConnection() {
        $this->conn = null;
        
        try {
            // PDO connection string using the defined variables
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            // Set error mode to throw exceptions on errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            die(); 
        }

        return $this->conn;
    }
}

?>