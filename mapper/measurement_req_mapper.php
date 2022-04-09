<?php
    class MeasurementRequestMapper {
        private $raw;

        public function __construct($raw) {
            $this->raw = $raw;
        }

        public function toMeasurement() {
            $json_dict = json_decode($this->raw, true);
            $device_id = $json_dict['device_id'];
            $value = $json_dict['value'];
            $measurement_builder = new MeasurementBuilder();
            $measurement_builder->withValue($value)->withDeviceId($device_id);
            return $measurement_builder->build();
        }
    }
?>