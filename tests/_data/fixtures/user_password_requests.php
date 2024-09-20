<?php

$data = <<<'JSON'
[
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/2\d{3}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


