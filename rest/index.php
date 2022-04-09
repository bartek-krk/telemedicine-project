<?php
    require_once('./../dto/healthcheck.php');
    header('Content-Type: application/json');
    $hc = new Healthcheck();
    echo $hc->json();
?>