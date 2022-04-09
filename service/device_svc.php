<?php
    function get_token($length) {
        $allowed_chars = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';
        $token = '';

        for ($i=0; $i < $length; $i++) { 
            $char = substr($allowed_chars, rand(0, strlen($allowed_chars)-1), 1);
            $token = $token.$char;
        }

        return $token;
    }

    class DeviceService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function addNew($device) {
            $name = $device->getName();
            $description = $device->getDescription();
            $unit = $device->getUnit();
            $token = get_token(10);

            $insert_sql = sprintf('INSERT INTO DEVICE(NAME, DESCRIPTION,UNIT, TOKEN) VALUES ("%s", "%s", "%s", "%s")', $name, $description, $unit, $token);
            $insert_res = $this->db->executeQuery($insert_sql);

            $fetch_sql = sprintf('SELECT * FROM DEVICE d WHERE d.NAME="%s" AND d.TOKEN="%s" LIMIT 1', $name, $token);
            $fetch_res = $this->db->executeQuery($fetch_sql);

            $device_builder = new DeviceBuilder();
            if(mysqli_num_rows($fetch_res) != 0) {
                $assoc_res = mysqli_fetch_assoc($fetch_res);
                $device_builder->withId($assoc_res['ID']);
                $device_builder->withName($assoc_res['NAME']);
                $device_builder->withDescription($row['DESCRIPTION']);
                $device_builder->withToken($assoc_res['TOKEN']);
                $device_builder->withUnit($row['UNIT']);
            }

            return $device_builder->build();

        }

        public function getAll() {
            $sql = 'SELECT * FROM DEVICE';
            $res = $this->db->executeQuery($sql);

            $res_arr = [];

            while($row = mysqli_fetch_assoc($res)) {
                $device_builder = new DeviceBuilder();
                $device_builder->withId($row['ID']);
                $device_builder->withName($row['NAME']);
                $device_builder->withDescription($row['DESCRIPTION']);
                $device_builder->withToken($row['TOKEN']);
                $device_builder->withUnit($row['UNIT']);
                array_push($res_arr, $device_builder->build());
            }

            return $res_arr;
        }

        public function existsById($id) {
            $sql = sprintf('SELECT COUNT(*) AS COUNT FROM DEVICE d WHERE d.ID=%d', $id);

            $result = $this->db->executeQuery($sql);
            $count = mysqli_fetch_assoc($result)['COUNT'];

            return $count == 1;
        }

        public function validateToken($device_id, $token) {
            $sql = sprintf('SELECT TOKEN FROM DEVICE d WHERE d.ID=%d', $device_id);
            $result = $this->db->executeQuery($sql);

            if (mysqli_num_rows($result) == 1) {
                $db_fetched_token = mysqli_fetch_assoc($result)['TOKEN'];
                return $db_fetched_token == $token;
            } else {
                return false;
            }
        }

        public function getById($device_id) {
            $sql = sprintf('SELECT * FROM DEVICE d WHERE d.ID=%d LIMIT 1', $device_id);
            $res = $this->db->executeQuery($sql);

            if (mysqli_num_rows($res) == 1) {
                $row = mysqli_fetch_assoc($res);
                $device_builder = new DeviceBuilder();
                $device_builder->withId($row['ID']);
                $device_builder->withName($row['NAME']);
                $device_builder->withDescription($row['DESCRIPTION']);
                $device_builder->withToken($row['TOKEN']);
                $device_builder->withUnit($row['UNIT']);
                return $device_builder->build();
            } else {
                return NULL;
            }
        }

    }
?>