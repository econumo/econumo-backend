<?php

$data = <<<'JSON'
[
  {
    "id": "2ec2df9d-240e-4355-bd8e-e0425fd72e1d",
    "plan_id": "16c88ac2-b548-4446-9e27-51a28156b299",
    "name": "Expenses",
    "position": 0,
    "created_at": "2023-10-15 02:23:25",
    "updated_at": "2023-10-15 02:23:25"
  },
  {
    "id": "cf328551-0808-4cb2-beb3-412be278a118",
    "plan_id": "3a6d84be-d074-4a14-ab9a-86dfb083c91d",
    "name": "Expenses",
    "position": 0,
    "created_at": "2023-10-15 02:23:40",
    "updated_at": "2023-10-15 02:23:40"
  },
  {
    "id": "949ec3ce-6379-406a-ae20-14ad63193d19",
    "plan_id": "bceed17e-d492-40be-921a-e7fa6f663fa6",
    "name": "Dany expenses",
    "position": 1,
    "created_at": "2023-10-15 02:24:21",
    "updated_at": "2023-10-15 02:24:21"
  },
  {
    "id": "e776079f-33a8-4c61-aa6c-21192daa50e7",
    "plan_id": "bceed17e-d492-40be-921a-e7fa6f663fa6",
    "name": "John expenses",
    "position": 0,
    "created_at": "2023-10-15 02:23:32",
    "updated_at": "2023-10-15 02:23:32"
  },
  {
    "id": "62ccc225-b141-42a4-8063-825c8b72d135",
    "plan_id": "bceed17e-d492-40be-921a-e7fa6f663fa6",
    "name": "Created folder",
    "position": 2,
    "created_at": "2023-10-15 02:23:32",
    "updated_at": "2023-10-15 02:23:32"
  },
  {
    "id": "860f0c50-bb33-42c1-955d-b3ce112462b8",
    "plan_id": "16c88ac2-b548-4446-9e27-51a28156b299",
    "name": "Empty folder",
    "position": 2,
    "created_at": "2023-10-15 02:23:32",
    "updated_at": "2023-10-15 02:23:32"
  }
]
JSON;

return json_decode($data, true);