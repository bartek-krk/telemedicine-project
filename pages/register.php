<?php
    require_once('./../utils/db_util.php');
    require_once('./../service/device_svc.php');
    require_once('./../config/config_provider.php');
    require_once('./../entity/device.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $config = new ConfigProvider('./../config/config.json');
        $db = new DbManager($config);
        $device_name = $_POST['device-name'];
        $device_description = $_POST['device-description'];
        $unit = $_POST['measurement-unit'];

        $device_builder = new DeviceBuilder();
        $device = $device_builder->withName($device_name)->withDescription($device_description)->withUnit($unit)->build();

        $device_svc = new DeviceService($db);

        $device = $device_svc->addNew($device);


        $success_message = sprintf(
            'Registered the device - %s (ID: %d, TOKEN: %s) - RETAIN THOSE DATA FOR UPLOADS', 
            $device->getName(), 
            $device->getId(), 
            $device->getToken()
        );
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
        <title>Register device</title>
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
            <h1>Register device</h1>
            <?php if (isset($success_message)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php } ?>

            <form action="./register.php" method="post">
                <div class="form-group">
                  <label for="device-name">Device name</label>
                  <input type="text" class="form-control" id="device-name" name="device-name" placeholder="Device name">
                  <small id="device-name-help" class="form-text text-muted">The name will be used to visually identify the device and is not unique.</small>
                </div>
                <div class="form-group">
                    <label for="device-description">Description</label>
                    <textarea class="form-control" id="device-description" name="device-description" device-description rows="3" placeholder="Description"></textarea>
                    <small id="device-description-help" class="form-text text-muted">Description helps people understand the purpose of your measurements.</small>
                </div>
                <div class="form-group">
                  <label for="measurement-unit">Measurement unit</label>
                  <input type="text" class="form-control" id="measurement-unit" name="measurement-unit" placeholder="Measurement unit">
                  <small id="measurement-unit-help" class="form-text text-muted">The unit your measurements will represent.</small>
                </div>
                <button type="submit" class="btn btn-primary">REGISTER</button>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    </body>
</html>