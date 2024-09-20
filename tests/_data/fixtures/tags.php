<?php

$data = <<<'JSON'
[
  {
    "id": "4a534bab-cc85-4aac-a024-b0659d378da7",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "NY-trip",
    "position": 0,
    "is_archived": 0,
    "created_at": "2024-08-26 05:06:46",
    "updated_at": "2024-08-31 22:38:54"
  },
  {
    "id": "91967a40-5d09-491d-a14d-bd31079e4846",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "EuroTrip",
    "position": 2,
    "is_archived": 0,
    "created_at": "2024-08-26 05:07:14",
    "updated_at": "2024-08-31 22:46:34"
  },
  {
    "id": "20323343-e63a-4006-bdd7-f0b7fa1526a5",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "NZ 2023",
    "position": 1,
    "is_archived": 1,
    "created_at": "2024-08-31 22:46:14",
    "updated_at": "2024-08-31 22:46:34"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


