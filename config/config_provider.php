<?php
    class ConfigProvider {

        private $json_dict;

        public function __construct($path) {
            $string = file_get_contents($path);
            $this->json_dict = json_decode($string, true);
        }

        public function getProperty($key) {
            return $this->json_dict[$key];
        }
    }
?>