<?php
    require_once('./../entity/measurement.php');
    require_once('./../entity/device.php');
    require_once('./../service/measurement_svc.php');
    require_once('./../service/device_svc.php');
    require_once('./../config/config_provider.php');
    require_once('./../utils/db_util.php');

    $DATETIME_FMT = 'd-m-Y H:i:s';

    $device_id = $_GET['id'];

    $config = new ConfigProvider('./../config/config.json');
    $db = new DbManager($config);
    $device_svc = new DeviceService($db);
    $measurement_svc = new MeasurementService($db, $device_svc);

    $device = $device_svc->getById($device_id);

    $measurements = $measurement_svc->getAllByDeviceId($device_id);

    $data_points = array();

    foreach ($measurements as $m) {
        array_push($data_points, array("y" => $m->getValue(), "label" => date($DATETIME_FMT, $m->getTimestamp())));
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .nav-right {
                margin-left: auto;
            }

            .nav-left {
                margin-right: auto;
            }
        </style>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Device <?php echo $device_id; ?></title>

        <script>
        window.onload = function () {
        
            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "IoT sensor measurements plot"
                },
                axisY: {
                    title: "Value [<?php echo $device->getUnit(); ?>]"
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($data_points, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            }
        </script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

            <ul class="navbar-nav nav-left">
                <li class="nav-item">
                    <a class="mx-2 navbar-brand pull-left" href="./index.php">IoT Monitor</a>
                </li>
            </ul>


            <ul class="navbar-nav nav-right align-items-center">
                <li class="nav-item">
                    <a href="./devices.php" class="btn btn-success mx-1" role="button">
                        SHOW AVAILABLE DEVICES
                    </a>
                </li>
            </ul>

        </nav>

        <div class="container">
            <h1>Measurements - <?php echo sprintf('%s (ID: %d)', $device->getName(), $device_id); ?></h1>
            <p>
                <?php echo sprintf('<b>Device description:</b> %s', $device->getDescription()); ?>
            </p>

            <div id="chartContainer" style="height: 370px; width: 100%;"></div>

            <table class="table table-hover">
                <tr>
                    <th>TIME</th>
                    <th>VALUE [<?php echo $device->getUnit(); ?>]</th>
                </tr>
                <?php foreach($measurements as $m) { ?>
                    <tr>
                        <td><?php echo date($DATETIME_FMT, $m->getTimestamp()) ?></td>
                        <td><?php echo $m->getValue() ?></td>
                <?php } ?>
            </table>
        </div>

        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    </body>
</html>