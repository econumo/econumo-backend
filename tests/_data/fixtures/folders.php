<?php

$data = <<<'JSON'
[
  {
    "id": "fe49bf88-0f8b-45b1-8feb-68eb38910e4d",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Personal",
    "position": 0,
    "is_visible": 1,
    "created_at": "2024-08-19 22:37:43",
    "updated_at": "2024-08-31 21:13:58"
  },
  {
    "id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Savings",
    "position": 1,
    "is_visible": 1,
    "created_at": "2024-08-19 22:37:48",
    "updated_at": "2024-08-31 21:14:04"
  },
  {
    "id": "ed3305b7-3f26-4520-a147-c8d80fa8f733",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "name": "Все счета",
    "position": 1000,
    "is_visible": 1,
    "created_at": "2024-09-02 22:19:46",
    "updated_at": "2024-09-02 22:19:46"
  },
  {
    "id": "383a8b79-7c48-4fb8-afbd-1c245778039a",
    "user_id": "2c652f2f-f958-40bc-8573-446f396f475d",
    "name": "Все счета",
    "position": 1000,
    "is_visible": 1,
    "created_at": "2024-09-16 22:17:19",
    "updated_at": "2024-09-16 22:17:19"
  },
  {
    "id": "bd996cab-a095-4e2b-929c-c990e5001bf9",
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "name": "Все счета",
    "position": 1000,
    "is_visible": 1,
    "created_at": "2024-09-16 22:22:30",
    "updated_at": "2024-09-16 22:22:30"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);