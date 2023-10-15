<?php

$data = <<<'JSON'
[
  {
    "id": "16c88ac2-b548-4446-9e27-51a28156b299",
    "user_id": "aff21334-96f0-4fb1-84d8-0223d0280954",
    "name": "Personal plan",
    "created_at": "2023-10-15 02:23:25",
    "updated_at": "2023-10-15 02:23:25"
  },
  {
    "id": "bceed17e-d492-40be-921a-e7fa6f663fa6",
    "user_id": "aff21334-96f0-4fb1-84d8-0223d0280954",
    "name": "Family plan",
    "created_at": "2023-10-15 02:23:32",
    "updated_at": "2023-10-15 02:23:32"
  },
  {
    "id": "3a6d84be-d074-4a14-ab9a-86dfb083c91d",
    "user_id": "aff21334-96f0-4fb1-84d8-0223d0280954",
    "name": "New family plan",
    "created_at": "2023-10-15 02:23:40",
    "updated_at": "2023-10-15 02:23:40"
  }
]
JSON;

return json_decode($data, true);


