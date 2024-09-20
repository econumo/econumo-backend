<?php

$data = <<<'JSON'
[
  {
    "id": "a5f4a984-948e-4617-bc27-1058d4c95586",
    "plan_id": "c7c5f148-7ea8-4654-a218-267d0bbf7e88",
    "name": "Expenses",
    "position": 0,
    "created_at": "2024-08-26 04:23:26",
    "updated_at": "2024-08-26 04:23:26"
  },
  {
    "id": "fbe8744e-73ec-4a4b-aabf-91b3e7c65bdd",
    "plan_id": "c7c5f148-7ea8-4654-a218-267d0bbf7e88",
    "name": "Trips",
    "position": 1,
    "created_at": "2024-08-31 21:20:52",
    "updated_at": "2024-08-31 21:20:52"
  },
  {
    "id": "e04beea3-6131-43ff-8d6f-6dc1b0e7cb8b",
    "plan_id": "bc976f50-ed28-4e52-8b6f-627a218ef685",
    "name": "Expenses",
    "position": 0,
    "created_at": "2024-08-31 22:53:52",
    "updated_at": "2024-08-31 22:53:52"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);