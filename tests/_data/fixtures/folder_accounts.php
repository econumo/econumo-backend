<?php

$data = <<<'JSON'
[
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "f22cfa46-e88c-4991-b937-35a2e85f2d52"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "da48d408-bf33-4e93-84a3-315536fc184d"
  },
  {
    "folder_id": "fe49bf88-0f8b-45b1-8feb-68eb38910e4d",
    "account_id": "b53cc423-4e33-49ba-98cc-ef80b2de9a86"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "28fcccba-21b2-4166-a213-bc4b7da9784d"
  },
  {
    "folder_id": "fe49bf88-0f8b-45b1-8feb-68eb38910e4d",
    "account_id": "9a922751-d24f-43d7-91db-257dfe15cce4"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "efe7c8b9-f602-4b58-b607-ffe64ea4b4a2"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "314863dc-3860-45fb-ad7f-7f920e491544"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "771008b4-2e61-4dfa-850e-55964cf1a964"
  },
  {
    "folder_id": "fe49bf88-0f8b-45b1-8feb-68eb38910e4d",
    "account_id": "473908a4-fba4-433c-9905-4ad6b0a42138"
  },
  {
    "folder_id": "fe49bf88-0f8b-45b1-8feb-68eb38910e4d",
    "account_id": "1bfe2f99-0ccd-4243-95c1-57aceac46409"
  },
  {
    "folder_id": "fe49bf88-0f8b-45b1-8feb-68eb38910e4d",
    "account_id": "a1328fd3-d241-4b9b-81a3-f5a694686d50"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "2f8fa6a5-34a1-4ea4-b3ec-e11e22201578"
  },
  {
    "folder_id": "6b404175-1a23-4a73-bd2c-db2491d37aa6",
    "account_id": "e91bc2d5-e791-40d0-9664-19fbae76d82e"
  },
  {
    "folder_id": "0f8ab340-73b8-449a-b2ab-1286d8e709fc",
    "account_id": "e91bc2d5-e791-40d0-9664-19fbae76d82e"
  },
  {
    "folder_id": "ed3305b7-3f26-4520-a147-c8d80fa8f733",
    "account_id": "fed3b875-808d-4f76-9c31-760aee6c09ed"
  },
  {
    "folder_id": "ed3305b7-3f26-4520-a147-c8d80fa8f733",
    "account_id": "da48d408-bf33-4e93-84a3-315536fc184d"
  },
  {
    "folder_id": "bd996cab-a095-4e2b-929c-c990e5001bf9",
    "account_id": "8a763fb8-053a-461f-9f35-04db372f2875"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);