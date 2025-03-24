<?php
    class Quote {
        //DB stuff
        private $conn;
        private $table = 'quotes';

        // Quote Properties
        public $id;
        public $author_id;
        public $author_name;
        public $category_id;
        public $category_name;
        public $quote;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Quote
        public function read() {
            // Create query
            $query = 'SELECT 
                    q.id,
                    q.quote,
                    q.author_id,
                    a.author as author_name,
                    q.category_id,
                    c.category as category_name
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a on q.author_id = a.id';
                    
            // Filter quotes
            $conditions = array();
            if ($this->author_id !== null) {
                $conditions[] = 'q.author_id = :author_id';
            }
            if ($this->category_id !== null) {
                $conditions[] = 'q.category_id = :category_id';
            }

            if (count($conditions) > 0) {
                $query .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $query .= ' ORDER BY q.id ASC';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind any set filters
            if ($this->author_id !== null) {
                $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
            }
            if ($this->category_id !== null) {
                $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            }

            //Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get Single Quote
        public function read_single() {
            // Create query
            $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author as author_name,
                    c.category as category_name
                FROM
                    ' . $this->table . ' q
                LEFT JOIN
                    categories c ON q.category_id = c.id
                LEFT JOIN
                    authors a on q.author_id = a.id
                WHERE
                    q.id = :id
                LIMIT 1';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            //Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            if ($row) {
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author_name = $row['author_name'];
                $this->category_name = $row['category_name'];
                return true;
            } else {
                return false;
            }
        }

        // Create Quote
        public function create() {
            // Check if author_id exists
            $query = 'SELECT id FROM authors WHERE id = :author_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'author_id Not Found']);
                exit();
            }

            // Check if category_id exists
            $query = 'SELECT id FROM categories WHERE id = :category_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'category_id Not Found']);
                exit();
            }
            
            // Create query
            $query = 'INSERT INTO ' . $this->table . ' (quote, category_id, author_id)
                    VALUES (:quote, :category_id, :author_id)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            
            // Bind data
            $stmt->bindValue(':quote', $this->quote, PDO::PARAM_STR);
            $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_STR);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update Quote
        public function update() {
            // Check if quote id exists
            $query = 'SELECT id FROM quotes WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'No Quotes Found']);
                exit();
            }

            // Check if author_id exists
            $query = 'SELECT id FROM authors WHERE id = :author_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'author_id Not Found']);
                exit();
            }

            // Check if category_id exists
            $query = 'SELECT id FROM categories WHERE id = :category_id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'category_id Not Found']);
                exit();
            }

            // Create query
            $query = 'UPDATE ' . $this->table . '
                SET
                    quote = :quote,
                    author_id = :author_id,
                    category_id = :category_id
                WHERE
                    id = :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Bind data
            $stmt->bindValue(':quote', $this->quote, PDO::PARAM_STR);
            $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_STR);

            // Execute query 
            if($stmt->execute()) {
                return true;
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Quote
        public function delete() {
            // Check if quote id exists
            $query = 'SELECT id FROM quotes WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo json_encode(['message' => 'No Quotes Found']);
                exit();
            }

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