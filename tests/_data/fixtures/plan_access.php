<?php

$data = <<<'JSON'
[
  {
    "plan_id": "3a6d84be-d074-4a14-ab9a-86dfb083c91d",
    "user_id": "77be9577-147b-4f05-9aa7-91d9b159de5b",
    "role": 1,
    "created_at": "2023-10-15 02:23:50",
    "updated_at": "2023-10-15 02:23:50",
    "is_accepted": 0
  },
  {
    "plan_id": "bceed17e-d492-40be-921a-e7fa6f663fa6",
    "user_id": "77be9577-147b-4f05-9aa7-91d9b159de5b",
    "role": 1,
    "created_at": "2023-10-15 02:23:46",
    "updated_at": "2023-10-15 02:24:21",
    "is_accepted": 1
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);