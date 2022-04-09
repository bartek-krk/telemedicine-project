<?php
    /**
     * Wrapper class for MySQLi queries.
     * 
     * @author Bartosz Lukasik
     */
    class DbManager {
        private $conn;

        /**
         * Default constructor - default values of connection credentials are deliberately
         * left blank - they MUST be set up in config file and are REQUIRED for the application
         * to serve its purpose.
         */
        public function __construct($configuration) {
            $host = $configuration->getProperty('datasource.host');
            $user = $configuration->getProperty('datasource.user');
            $password = $configuration->getProperty('datasource.password');
            $schema = $configuration->getProperty('datasource.schema');
            $conn = mysqli_connect($host, $user, $password, $schema);

            if(!$conn) {
                die('Database connection failed');
            } else {
                $this->conn = $conn;
            }
        }

        /**
         * Returns MySQLi result, given a valid SQL query.
         */
        public function executeQuery($query) {
            return mysqli_query($this->conn, $query);
        }

        public function __destruct() {
            $this->conn->close();
        }
    }
?>