<?php
    require_once('./../utils/db_util.php');
    require_once('./../service/device_svc.php');
    require_once('./../config/config_provider.php');
    require_once('./../entity/device.php');

    $config = new ConfigProvider('./../config/config.json');
    $db = new DbManager($config);
    $device_svc = new DeviceService($db);

    $devices = $device_svc->getAll();
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
        <title>Devices - list</title>
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
                    <a href="./register.php" class="btn btn-danger mx-1" role="button">
                        REGISTER NEW DEVICE
                    </a>
                </li>
            </ul>
        </nav>

        <div class="container">
            <h1>Available devices</h1>
            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <th>SEE MEASUREMENTS</th>
                </tr>
                <?php foreach($devices as $d) { ?>
                    <tr>
                        <td><?php echo $d->getId() ?></td>
                        <td><?php echo $d->getName() == '' ? '< name blank >' : $d->getName() ?></td>
                        <td><?php echo $d->getDescription() == '' ? '< description blank >' : $d->getDescription() ?></td>
                        <td>
                            <form action="./measurements.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $d->getId() ?>">
                                <button class="btn btn-secondary" type="submit">SEE MEASUREMENTS</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    </body>
</html>