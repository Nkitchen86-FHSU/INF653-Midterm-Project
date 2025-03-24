<?php
    class Author {
        //DB stuff
        private $conn;
        private $table = 'authors';

        // Author Properties
        public $id;
        public $author;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Author
        public function read() {
            // Create query
            $query = 'SELECT 
                    a.id,
                    a.author
                FROM
                    ' . $this->table . ' a
                ORDER BY
                    a.id ASC';
                    
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Author
        public function read_single() {
            // Create query
            $query = 'SELECT 
                    a.id,
                    a.author
                FROM
                    ' . $this->table . ' a
                WHERE
                    a.id = :id
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
                $this->author = $row['author'];
                return true;
            } else {
                return false;
            }
        }

        // Create Author
        public function create() {
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (author)
            VALUES (:author)';
    
            // Prepare statement
            $stmt = $this->conn->prepare($query);
    
            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
    
            // Bind data
            $stmt->bindValue(':author', $this->author, PDO::PARAM_STR);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            echo json_encode(['message' => 'Author Not Created']);
            return false;
        }

        // Update Author
        public function update() {
            // Check if author_id exists
            $query = 'SELECT id FROM authors WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'author_id Not Found']);
                exit();
            }

            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET
                    author = :author
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Bind data
            $stmt->bindValue(':author', $this->author, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Author
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