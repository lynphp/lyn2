<?php
use app\backend\models\ShoeBackend;
require '../../testBase.php';
//ShoeBackend::seedData();

$shoe = new \App\models\Shoe();
$shoes = $shoe->getShoes();
foreach ($shoes  as $shoe) {
    echo $shoe->id;
}