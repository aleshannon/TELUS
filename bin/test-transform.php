<?php
require dirname(__DIR__) . '/config/bootstrap.php';

use App\Service\DataTransformer;

$transformer = new DataTransformer();
$transformer->jsonToCsv('data_20240727.json', 'ETL_20240727.csv');
$transformer->createSummary('data_20240727.json', 'summary_20240727.csv');

echo "Transformation and summary complete\n";
