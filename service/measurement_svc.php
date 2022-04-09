<?php
    require_once('./../service/device_svc.php');

    class MeasurementUploadErrorCode {
       const SUCCESS = 0;
       const NONEXISTENT_DEVICE = 1;
       const INTERNAL_SERVER_ERROR = 2;
    }
    
    class MeasurementUploadErrorCodeMessageResolver {
        public static function resolve($errorCode, $entity) {
            switch ($errorCode) {
                case MeasurementUploadErrorCode::SUCCESS:
                    return new MeasurementUploadMessage(201, 'New measurement created successfully', $entity);
                case MeasurementUploadErrorCode::NONEXISTENT_DEVICE:
                    return new MeasurementUploadMessage(404, 'Device with given ID does not exist', $entity);
                case MeasurementUploadErrorCode::INTERNAL_SERVER_ERROR:
                    return new MeasurementUploadMessage(500, 'Internal Server Error', $entity);
                default:
                    return new MeasurementUploadMessage(422, 'Unknown Error', $entity);
            }
        }
    }

    class MeasurementUploadResponse {

        private $is_success;
        private $error_code;
        private $success_entity;

        public function __construct($is_success, $error_code, $success_entity) {
            $this->is_success = $is_success;
            $this->error_code = $error_code;
            $this->success_entity = $success_entity;
        }

        public function isSuccess() {
            return $this->is_success;
        }

        public function getErrorCode() {
            return $this->error_code;
        }

        public function getSuccessEntity() {
            return $this->success_entity;
        }

    }


    class MeasurementService {
        private $db;
        private $device_svc;

        public function __construct($db, $device_svc) {
            $this->db = $db;
            $this->device_svc = $device_svc;
        }
        
        public function addNew($measurement) {
            $device_id = $measurement->getDeviceId();
            $value = $measurement->getValue();

            $device_exists = $this->device_svc->existsById($device_id);
            if (!$device_exists) {
                return new MeasurementUploadResponse(false, MeasurementUploadErrorCode::NONEXISTENT_DEVICE, NULL);
            } else {
                try {
                    $insert_sql = sprintf('INSERT INTO MEASUREMENT(VALUE, DEVICE_ID, TIMESTAMP) VALUES (%.5F, %d, %d)', $value, $device_id, time());
                    $insert_res = $this->db->executeQuery($insert_sql);

                    if (!$insert_res) {
                        return new MeasurementUploadResponse(false, MeasurementUploadErrorCode::INTERNAL_SERVER_ERROR, NULL);
                    }

                    $select_sql = sprintf('SELECT * FROM MEASUREMENT m WHERE m.VALUE=%.5F AND m.DEVICE_ID=%d ORDER BY m.TIMESTAMP DESC', $value, $device_id);
                    $select_res = $this->db->executeQuery($select_sql);

                    if (!$select_res) {
                        return new MeasurementUploadResponse(false, MeasurementUploadErrorCode::INTERNAL_SERVER_ERROR, NULL);
                    }

                    $row = mysqli_fetch_assoc($select_res);

                    return new MeasurementUploadResponse(
                        true, 
                        MeasurementUploadErrorCode::SUCCESS, 
                        new Measurement($row['ID'], $row['VALUE'], $row['DEVICE_ID'], $row['TIMESTAMP'])
                    );
                } catch (Exception $e) {
                    return new MeasurementUploadResponse(false, MeasurementUploadErrorCode::INTERNAL_SERVER_ERROR, NULL);
                }
            }
        }

        public function getAllByDeviceId($device_id) {
            $sql = sprintf('SELECT * FROM MEASUREMENT m WHERE m.DEVICE_ID=%d ORDER BY m.TIMESTAMP ASC', $device_id);
            $res = $this->db->executeQuery($sql);

            $res_arr = [];

            while($row = mysqli_fetch_assoc($res)) {
                $measurement_builder = new MeasurementBuilder();
                $measurement_builder->withId($row['ID']);
                $measurement_builder->withValue($row['VALUE']);
                $measurement_builder->withDeviceId($row['DEVICE_ID']);
                $measurement_builder->withTimestamp($row['TIMESTAMP']);
                array_push($res_arr, $measurement_builder->build());
            }

            return $res_arr;
        }
    }
?>