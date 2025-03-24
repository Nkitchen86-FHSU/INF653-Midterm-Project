<?php
    class Category {
        //DB stuff
        private $conn;
        private $table = 'categories';

        // Category Properties
        public $id;
        public $category;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Category
        public function read() {
            // Create query
            $query = 'SELECT 
                    c.id,
                    c.category
                FROM
                    ' . $this->table . ' c
                ORDER BY
                    c.id ASC';
                    
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Category
        public function read_single() {
            // Create query
            $query = 'SELECT 
                    c.id,
                    c.category
                FROM
                    ' . $this->table . ' c
                WHERE
                    c.id = :id
                LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            //Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            if($row){
                $this->id = $row['id'];
                $this->category = $row['category'];
                return true;
            } else {
                return false;
            }
        }

        // Create Category
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (category)
                    VALUES (:category)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            
            // Bind data
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update Category
        public function update() {
            // Check if category_id exists
            $query = 'SELECT id FROM categories WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'category_id Not Found']);
                exit();
            }

            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET
                    category = :category
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Bind data
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Category
        public function delete() {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean ID
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind ID
            $stmt->bindParam(':id', $this->id);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }