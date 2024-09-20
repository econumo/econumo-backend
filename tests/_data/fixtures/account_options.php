<?php

$data = <<<'JSON'
[
  {
    "account_id": "b53cc423-4e33-49ba-98cc-ef80b2de9a86",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 0,
    "created_at": "2024-08-19 22:28:40",
    "updated_at": "2024-08-19 22:38:00"
  },
  {
    "account_id": "2f8fa6a5-34a1-4ea4-b3ec-e11e22201578",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 3,
    "created_at": "2024-08-19 22:35:37",
    "updated_at": "2024-09-02 22:24:32"
  },
  {
    "account_id": "da48d408-bf33-4e93-84a3-315536fc184d",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 4,
    "created_at": "2024-08-19 22:36:39",
    "updated_at": "2024-09-02 22:24:32"
  },
  {
    "account_id": "f22cfa46-e88c-4991-b937-35a2e85f2d52",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 6,
    "created_at": "2024-08-19 22:37:36",
    "updated_at": "2024-08-26 05:04:14"
  },
  {
    "account_id": "28fcccba-21b2-4166-a213-bc4b7da9784d",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 7,
    "created_at": "2024-08-19 22:40:26",
    "updated_at": "2024-08-26 05:04:14"
  },
  {
    "account_id": "9a922751-d24f-43d7-91db-257dfe15cce4",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 4,
    "created_at": "2024-08-19 22:44:06",
    "updated_at": "2024-08-19 22:58:53"
  },
  {
    "account_id": "efe7c8b9-f602-4b58-b607-ffe64ea4b4a2",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 9,
    "created_at": "2024-08-19 22:45:19",
    "updated_at": "2024-08-26 05:04:14"
  },
  {
    "account_id": "1bfe2f99-0ccd-4243-95c1-57aceac46409",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 5,
    "created_at": "2024-08-19 22:48:16",
    "updated_at": "2024-08-19 22:58:53"
  },
  {
    "account_id": "314863dc-3860-45fb-ad7f-7f920e491544",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 5,
    "created_at": "2024-08-19 22:50:02",
    "updated_at": "2024-08-26 05:04:14"
  },
  {
    "account_id": "771008b4-2e61-4dfa-850e-55964cf1a964",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 8,
    "created_at": "2024-08-19 22:53:51",
    "updated_at": "2024-08-26 05:04:14"
  },
  {
    "account_id": "473908a4-fba4-433c-9905-4ad6b0a42138",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 3,
    "created_at": "2024-08-19 22:54:26",
    "updated_at": "2024-08-19 22:58:53"
  },
  {
    "account_id": "a1328fd3-d241-4b9b-81a3-f5a694686d50",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 1,
    "created_at": "2024-08-19 22:58:42",
    "updated_at": "2024-08-31 21:12:49"
  },
  {
    "account_id": "e91bc2d5-e791-40d0-9664-19fbae76d82e",
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "position": 4,
    "created_at": "2024-08-19 23:04:51",
    "updated_at": "2024-08-31 21:13:03"
  },
  {
    "account_id": "fed3b875-808d-4f76-9c31-760aee6c09ed",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "position": 0,
    "created_at": "2024-09-02 22:20:22",
    "updated_at": "2024-09-02 22:20:22"
  },
  {
    "account_id": "da48d408-bf33-4e93-84a3-315536fc184d",
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "position": 1,
    "created_at": "2024-09-02 22:25:04",
    "updated_at": "2024-09-02 22:25:04"
  },
  {
    "account_id": "8a763fb8-053a-461f-9f35-04db372f2875",
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "position": 0,
    "created_at": "2024-09-17 00:34:25",
    "updated_at": "2024-09-17 00:34:25"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


