<?php
    class RestError {
        public $code;
        public $message;
        public $description;
        public $timestamp;

        public function __construct($code, $message, $description) {
            $this->code = $code;
            $this->message = $message;
            $this->description = $description;
            $this->timestamp = time();
        }

        public function json() {
            return json_encode($this);
        }
    }
?>