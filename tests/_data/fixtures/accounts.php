<?php

$data = <<<'JSON'
[
  {
    "id": "b53cc423-4e33-49ba-98cc-ef80b2de9a86",
    "currency_id": "e54f14e4-cdd3-4095-a892-ae7f532aaf7c",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Checking",
    "balance": 731.00,
    "type": 2,
    "icon": "wallet",
    "is_excluded_from_budget": 0,
    "is_deleted": 0,
    "created_at": "2024-08-19 22:28:40",
    "updated_at": "2024-08-31 21:14:34"
  },
  {
    "id": "2f8fa6a5-34a1-4ea4-b3ec-e11e22201578",
    "currency_id": "e54f14e4-cdd3-4095-a892-ae7f532aaf7c",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Savings",
    "balance": 1500.00,
    "type": 2,
    "icon": "savings",
    "is_excluded_from_budget": 0,
    "is_deleted": 0,
    "created_at": "2024-08-19 22:35:37",
    "updated_at": "2024-08-19 22:35:37"
  },
  {
    "id": "da48d408-bf33-4e93-84a3-315536fc184d",
    "currency_id": "e54f14e4-cdd3-4095-a892-ae7f532aaf7c",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Family savings",
    "balance": 2250.00,
    "type": 2,
    "icon": "volunteer_activism",
    "is_excluded_from_budget": 0,
    "is_deleted": 0,
    "created_at": "2024-08-19 22:36:39",
    "updated_at": "2024-08-19 22:42:39"
  },
  {
    "id": "a1328fd3-d241-4b9b-81a3-f5a694686d50",
    "currency_id": "e54f14e4-cdd3-4095-a892-ae7f532aaf7c",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "Credit card",
    "balance": -433.00,
    "type": 2,
    "icon": "credit_card",
    "is_excluded_from_budget": 0,
    "is_deleted": 0,
    "created_at": "2024-08-19 22:58:42",
    "updated_at": "2024-08-19 22:58:42"
  },
  {
    "id": "fed3b875-808d-4f76-9c31-760aee6c09ed",
    "currency_id": "e54f14e4-cdd3-4095-a892-ae7f532aaf7c",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "name": "Checking",
    "balance": 6662.00,
    "type": 2,
    "icon": "home",
    "is_excluded_from_budget": 0,
    "is_deleted": 0,
    "created_at": "2024-09-02 22:20:22",
    "updated_at": "2024-09-02 22:20:22"
  },
  {
    "id": "8a763fb8-053a-461f-9f35-04db372f2875",
    "currency_id": "494d0545-f8e9-48f8-82c4-99130c902a1b",
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "name": "Checking",
    "balance": 16815.45,
    "type": 2,
    "icon": "home",
    "is_excluded_from_budget": 0,
    "is_deleted": 0,
    "created_at": "2024-09-17 00:34:25",
    "updated_at": "2024-09-17 00:34:25"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


