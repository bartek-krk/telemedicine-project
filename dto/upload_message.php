<?php
    class CreatedEntity {
        public $id;
        public $value;
        public $device_id;

        public function __construct($id, $value, $device_id) {
            $this->id = $id;
            $this->value = $value;
            $this->device_id = $device_id;
        }
    }

    class MeasurementUploadMessage {
        public $code;
        public $message;
        public $created_entity;
        public $timestamp;

        public function __construct($code, $message, $entity) {
            $this->code = $code;
            $this->message = $message;
            $this->created_entity = $entity == NULL ? NULL : new CreatedEntity($entity->getId(), $entity->getValue(), $entity->getDeviceId());
            $this->timestamp = time();
        }

        public function json() {
            return json_encode($this);
        }
    }

?>