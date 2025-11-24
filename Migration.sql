

-- 1. Brands Table
CREATE TABLE Brands (
    brand_id INT AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(100) NOT NULL UNIQUE
);

-- 2. Categories Table
CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

-- 3. Conditions Table
CREATE TABLE Conditions (
    condition_id INT AUTO_INCREMENT PRIMARY KEY,
    condition_name VARCHAR(50) NOT NULL UNIQUE
);

-- 4. Products Table (The Core)
CREATE TABLE Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    brand_id INT NOT NULL,
    category_id INT NOT NULL,
    condition_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    discounted_price DECIMAL(10, 2),
    age VARCHAR(50),
    warranty_status VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES Brands(brand_id),
    FOREIGN KEY (category_id) REFERENCES Categories(category_id),
    FOREIGN KEY (condition_id) REFERENCES Conditions(condition_id)
);

-- 5. Device Specifics Table
CREATE TABLE Device_Specifics (
    specific_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNIQUE,
    imei_number VARCHAR(50) UNIQUE,
    battery_health INT,
    storage_variant VARCHAR(50),
    color VARCHAR(50),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

CREATE TABLE Product_Photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    is_main BOOLEAN DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Optional: Initial Seed Data for lookups
INSERT INTO Brands (brand_name) VALUES ('Apple'), ('Samsung'), ('Google'), ('OnePlus'), ('Vivo'),('Oppo');
INSERT INTO Categories (category_name) VALUES ('Smartphone'), ('Tablet'), ('Smart Watch'), ('Accessory');
INSERT INTO Conditions (condition_name) VALUES ('Seal Pack'), ('Mint'), ('Super Mint'), ('Good');