<?php

$data = <<<'JSON'
[
  {
    "id": "c7c5f148-7ea8-4654-a218-267d0bbf7e88",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "My Budget",
    "start_date": "2024-08-01 00:00:00",
    "created_at": "2024-08-26 04:23:26",
    "updated_at": "2024-08-31 22:53:46"
  },
  {
    "id": "bc976f50-ed28-4e52-8b6f-627a218ef685",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Family",
    "start_date": "2024-08-31 22:53:52",
    "created_at": "2024-08-31 22:53:52",
    "updated_at": "2024-08-31 22:53:52"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


