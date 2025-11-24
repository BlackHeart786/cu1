<?php

class Product {
    private $conn;
    private $table = 'Products';

    public function __construct($db) {
        $this->conn = $db;
    }

   
    public function readOne($product_id) {
        $query = "
            SELECT
                p.title, p.price, p.discounted_price, p.age, p.warranty_status, p.description,
                b.brand_name, 
                cg.category_name,
                cn.condition_name,
                ds.imei_number, ds.battery_health, ds.storage_variant, ds.color
            FROM
                " . $this->table . " p
            JOIN
                Brands b ON p.brand_id = b.brand_id
            JOIN
                Categories cg ON p.category_id = cg.category_id
            JOIN
                Conditions cn ON p.condition_id = cn.condition_id
            LEFT JOIN
                Device_Specifics ds ON p.product_id = ds.product_id
            WHERE
                p.product_id = :id
            LIMIT
                0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $product_id);

        $stmt->execute();

        return $stmt;
    }
    public function readByCategory($category_id) {
    $query = "
        SELECT
            p.product_id, p.title, p.price, p.discounted_price,
            b.brand_name, 
            ph.photo_url AS main_photo_url
        FROM
            " . $this->table . " p
        JOIN
            Brands b ON p.brand_id = b.brand_id
        LEFT JOIN
            Product_Photos ph ON p.product_id = ph.product_id AND ph.is_main = 1
        WHERE
            p.category_id = :category_id
        ORDER BY 
            p.created_at DESC"; // Show newest items first

    $stmt = $this->conn->prepare($query);
    // Bind the category ID
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    $stmt->execute();
    
    return $stmt; // Returns the result set to be fetched in your calling script
}   

private function getLookupId($table, $name_column, $name_value) {
        $query = "SELECT " . $table . "_id FROM " . $table . " WHERE " . $name_column . " = :name LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name_value);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row[$table . "_id"] : null;
    }

    public function create($data) {
        // Start Transaction
        $this->conn->beginTransaction();
        
        try {
            // 1. Get IDs from lookup tables (Assuming $data holds the names for simplicity)
            // In a real application, you'd usually pass the IDs directly from the Admin UI.
            $brand_id = $this->getLookupId('Brands', 'brand_name', $data['brand_name']);
            $category_id = $this->getLookupId('Categories', 'category_name', $data['category_name']);
            $condition_id = $this->getLookupId('Conditions', 'condition_name', $data['condition_name']);

            // Simple validation check for IDs
            if (!$brand_id || !$category_id || !$condition_id) {
                throw new Exception("Missing or invalid Brand, Category, or Condition.");
            }

            // 2. Insert into Products table
            $product_query = "
                INSERT INTO " . $this->table . " 
                (title, brand_id, category_id, condition_id, price, discounted_price, age, warranty_status, description)
                VALUES 
                (:title, :brand_id, :category_id, :condition_id, :price, :d_price, :age, :warranty, :description)";

            $stmt_product = $this->conn->prepare($product_query);
            
            // Bind parameters for Products table
            $stmt_product->bindParam(':title', $data['title']);
            $stmt_product->bindParam(':brand_id', $brand_id);
            $stmt_product->bindParam(':category_id', $category_id);
            $stmt_product->bindParam(':condition_id', $condition_id);
            $stmt_product->bindParam(':price', $data['price']);
            $stmt_product->bindParam(':d_price', $data['discounted_price']);
            $stmt_product->bindParam(':age', $data['age']);
            $stmt_product->bindParam(':warranty', $data['warranty_status']);
            $stmt_product->bindParam(':description', $data['description']);

            $stmt_product->execute();
            
            // Get the ID of the product we just inserted
            $last_product_id = $this->conn->lastInsertId();

            // 3. Insert into Device_Specifics table (Only if needed, e.g., if IMEI is provided)
            if (!empty($data['imei_number'])) {
                $specifics_query = "
                    INSERT INTO Device_Specifics 
                    (product_id, imei_number, battery_health, storage_variant, color)
                    VALUES 
                    (:product_id, :imei_number, :battery_health, :storage_variant, :color)";

                $stmt_specifics = $this->conn->prepare($specifics_query);

                // Bind parameters for Device_Specifics table
                $stmt_specifics->bindParam(':product_id', $last_product_id);
                $stmt_specifics->bindParam(':imei_number', $data['imei_number']);
                $stmt_specifics->bindParam(':battery_health', $data['battery_health'], PDO::PARAM_INT);
                $stmt_specifics->bindParam(':storage_variant', $data['storage_variant']);
                $stmt_specifics->bindParam(':color', $data['color']);

                $stmt_specifics->execute();
            }

            // 4. If everything worked, commit the changes
            $this->conn->commit();
            return true; 

        } catch (Exception $e) {
            // Something went wrong, rollback all inserts/updates
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            // Log the error
            error_log("Product creation failed: " . $e->getMessage());
            return false;
        }
    }
    

}


?>