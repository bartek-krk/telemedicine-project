<?php
    class Measurement {
        private $id;
        private $value;
        private $device_id;
        private $timestamp;

        public function __construct($id, $value, $device_id, $timestamp) {
            $this->id = $id;
            $this->value = $value;
            $this->device_id = $device_id;
            $this->timestamp = $timestamp;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setValue($value) {
            $this->value = $value;
        }

        public function setDeviceId($device_id) {
            $this->device_id = $device_id;
        }

        public function setTimestamp($timestamp) {
            $this->timestamp = $timestamp;
        }

        public function getId() {
            return $this->id;
        }

        public function getValue() {
            return $this->value;
        }

        public function getDeviceId() {
            return $this->device_id;
        }

        public function getTimestamp() {
            return $this->timestamp;
        }
    }

    class MeasurementBuilder {
        private $instance;

        public function __construct() {
            $this->instance = new Measurement(0, 0.0, 0, 0);
        }

        public function withId($id) {
            $this->instance->setId($id);
            return $this;
        }

        public function withValue($value) {
            $this->instance->setValue($value);
            return $this;
        }

        public function withDeviceId($device_id) {
            $this->instance->setDeviceId($device_id);
            return $this;
        }

        public function withTimestamp($timestamp) {
            $this->instance->setTimestamp($timestamp);
            return $this;
        }

        public function build() {
            return $this->instance;
        }
    }
?>