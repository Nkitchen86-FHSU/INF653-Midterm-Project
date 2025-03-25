<?php
    class Database {
        // DB Params
        private $conn;
        private $host;
        private $port;
        private $db_name;
        private $username;
        private $password;

        // DB Get Credentials
        public function __construct() {
            $db_url = getenv('DATABASE_URL');
            $url = parse_url($db_url);
            $this->host = $url['host'];
            $this->port = isset($url['port']) ? $url['port'] : 5432;
            $this->db_name = ltrim($url['path'], '/');
            $this->username = $url['user'];
            $this->password = $url['pass'];
        }

        // DB Connect
        public function connect() {
            $this->conn = null;
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";

            try {
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }

            return $this->conn;
        }
    }