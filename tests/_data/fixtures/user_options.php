<?php

$data = <<<'JSON'
[
  {
    "id": "d7522c83-67a9-47be-aca6-6060f9d9d1ea",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "currency",
    "value": "USD",
    "created_at": "2024-08-19 03:38:21",
    "updated_at": "2024-08-19 03:38:21"
  },
  {
    "id": "5e8be811-b737-451e-ab9a-1a9dfef3a7cb",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "report_period",
    "value": "monthly",
    "created_at": "2024-08-19 03:38:21",
    "updated_at": "2024-08-19 03:38:21"
  },
  {
    "id": "7dc4cb7e-98a1-4fb5-bca1-d3b9ae374ca6",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "default_plan",
    "value": "bc976f50-ed28-4e52-8b6f-627a218ef685",
    "created_at": "2024-08-19 03:38:21",
    "updated_at": "2024-08-31 22:54:31"
  },
  {
    "id": "47bf1e8b-ef97-4784-b7fa-63aada9e6a2a",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "name": "currency",
    "value": "USD",
    "created_at": "2024-09-02 22:19:46",
    "updated_at": "2024-09-02 22:19:46"
  },
  {
    "id": "7137e87a-23e8-4461-b166-7eb99683918c",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "name": "report_period",
    "value": "monthly",
    "created_at": "2024-09-02 22:19:46",
    "updated_at": "2024-09-02 22:19:46"
  },
  {
    "id": "11088910-88cb-4b7a-9386-d5ba2c2e2110",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "name": "default_plan",
    "value": null,
    "created_at": "2024-09-02 22:19:46",
    "updated_at": "2024-09-02 22:19:46"
  },
  {
    "id": "e8acc4ba-8d4d-44f8-b2e6-3fe8751416fd",
    "user_id": "2c652f2f-f958-40bc-8573-446f396f475d",
    "name": "currency",
    "value": "USD",
    "created_at": "2024-09-16 22:17:19",
    "updated_at": "2024-09-16 22:17:19"
  },
  {
    "id": "10cb521c-5280-4d51-9e84-a8824bbdb1ad",
    "user_id": "2c652f2f-f958-40bc-8573-446f396f475d",
    "name": "report_period",
    "value": "monthly",
    "created_at": "2024-09-16 22:17:19",
    "updated_at": "2024-09-16 22:17:19"
  },
  {
    "id": "27cfbcce-1028-476f-bb8b-33efc80025a7",
    "user_id": "2c652f2f-f958-40bc-8573-446f396f475d",
    "name": "default_plan",
    "value": null,
    "created_at": "2024-09-16 22:17:19",
    "updated_at": "2024-09-16 22:17:19"
  },
  {
    "id": "9dfae532-495a-43b4-96c6-ca0478287b1f",
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "name": "currency",
    "value": "USD",
    "created_at": "2024-09-16 22:22:30",
    "updated_at": "2024-09-16 22:22:30"
  },
  {
    "id": "dd5b09a6-27aa-44f0-b22f-8c9d653991e2",
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "name": "report_period",
    "value": "monthly",
    "created_at": "2024-09-16 22:22:30",
    "updated_at": "2024-09-16 22:22:30"
  },
  {
    "id": "0fade6dc-cf80-4983-854d-12694d56b319",
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "name": "default_plan",
    "value": null,
    "created_at": "2024-09-16 22:22:30",
    "updated_at": "2024-09-16 22:22:30"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


