<?php
    require_once('./../dto/rest_error.php');
    require_once('./../utils/json_util.php');
    require_once('./../entity/measurement.php');
    require_once('./../mapper/measurement_req_mapper.php');
    require_once('./../dto/upload_message.php');
    require_once('./../service/measurement_svc.php');
    require_once('./../config/config_provider.php');
    require_once('./../utils/db_util.php');
    require_once('./../service/device_svc.php');


    $config = new ConfigProvider('./../config/config.json');
    $db = new DbManager($config);

    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        $err = new RestError(405, 'Method Not Allowed', 'This method will allow only POST requests');
        echo $err->json();
        http_response_code(405);
        exit();
    }

    $raw_data = file_get_contents('php://input');
    // Corresponds to "Svc-Token" header name
    $token = $_SERVER['HTTP_SVC_TOKEN'];

    if (!json_validator($raw_data) || !$token) {
        $err = new RestError(400, 'Bad Request', 'JSON format is broken or required headers are missing');
        echo $err->json();
        http_response_code(400);
        exit();
    }

    $mapper = new MeasurementRequestMapper($raw_data);
    $measurement = $mapper->toMeasurement();

    $device_svc = new DeviceService($db);
    $is_authorized = $device_svc->validateToken($measurement->getDeviceId(), $token);

    if(!$is_authorized) {
        $err = new RestError(401, 'Unauthorized', 'Unauthorized to perform the operation');
        echo $err->json();
        http_response_code(401);
        exit();
    }

    $measurement_svc = new MeasurementService($db, $device_svc);
    $svc_response = $measurement_svc->addNew($measurement);

    $api_response = MeasurementUploadErrorCodeMessageResolver::resolve($svc_response->getErrorCode(), $svc_response->getSuccessEntity());
    http_response_code($api_response->code);
    echo $api_response->json();

?>