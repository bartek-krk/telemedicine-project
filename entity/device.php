<?php
    class Device {
        private $id;
        private $name;
        private $token;
        private $description;
        private $unit;

        public function __construct($id, $name, $token, $description, $unit) {
            $this->id = $id;
            $this->name = $name;
            $this->token = $token;
            $this->description = $description;
            $this->unit = $unit;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setToken($token) {
            $this->token = $token;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setUnit($unit) {
            $this->unit = $unit;
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getToken() {
            return $this->token;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getUnit() {
            return $this->unit;
        }
    }

    class DeviceBuilder {

        private $instance;

        public function __construct() {
            $this->instance = new Device(0, '', '', '', '');
        }

        public function withId($id) {
            $this->instance->setId($id);
            return $this;
        }

        public function withName($name) {
            $this->instance->setName($name);
            return $this;
        }

        public function withToken($token) {
            $this->instance->setToken($token);
            return $this;
        }

        public function withDescription($description) {
            $this->instance->setDescription($description);
            return $this;
        }

        public function withUnit($unit) {
            $this->instance->setUnit($unit);
            return $this;
        }

        public function build() {
            return $this->instance;
        }
    }
?>