<?php

$data = <<<'JSON'
[
  {
    "account_id": "da48d408-bf33-4e93-84a3-315536fc184d",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "role": 0,
    "created_at": "2024-09-02 22:25:04",
    "updated_at": "2024-09-02 22:25:04"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


