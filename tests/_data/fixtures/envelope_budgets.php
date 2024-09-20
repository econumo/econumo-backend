<?php

$data = <<<'JSON'
[
  {
    "id": "f6d65795-0486-4246-9d23-4d72ed19c91d",
    "envelope_id": "52d14a93-7a1f-4a21-9f0b-d93d03b03c8b",
    "amount": 800.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:25:51",
    "updated_at": "2024-08-31 22:42:50"
  },
  {
    "id": "b7c2db74-fa60-48f5-b5cc-6c2f21be36d2",
    "envelope_id": "6f0365bc-2fe3-471d-aec2-09d4dd885112",
    "amount": 1500.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:00",
    "updated_at": "2024-08-31 21:26:00"
  },
  {
    "id": "bea3faf3-e8a4-423a-8de0-d7ce705468db",
    "envelope_id": "3edb6304-9d70-4b98-a892-4bb0d9f3c58e",
    "amount": 150.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:04",
    "updated_at": "2024-08-31 21:32:14"
  },
  {
    "id": "4405523d-5ad8-460c-b1cc-add6237e11a1",
    "envelope_id": "59d59113-a1ef-49c5-bbd5-c62aa6bf5fbb",
    "amount": 100.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:08",
    "updated_at": "2024-08-31 21:32:04"
  },
  {
    "id": "81b0482e-859a-4b33-9ab0-20e9ab746290",
    "envelope_id": "d4471d0e-74b1-421e-ac44-7c3be5f9b12b",
    "amount": 2850.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:12",
    "updated_at": "2024-08-31 21:31:58"
  },
  {
    "id": "698e21a8-6696-4321-8a80-9f3af8c7ba7b",
    "envelope_id": "d13ce8b9-d0ab-4757-b9f8-28c0ea639db0",
    "amount": 100.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:16",
    "updated_at": "2024-08-31 21:26:16"
  },
  {
    "id": "18e43038-5e5f-472f-af0a-6a6010a5e7b1",
    "envelope_id": "c6ab5aa9-27ba-4ead-8660-de509ccc6418",
    "amount": 100.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:29",
    "updated_at": "2024-08-31 21:32:23"
  },
  {
    "id": "0205c156-dc28-4db4-bd00-9b2b43dbe3af",
    "envelope_id": "4d32c8a8-968f-4955-b892-679adfdcb986",
    "amount": 50.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:33",
    "updated_at": "2024-08-31 22:43:27"
  },
  {
    "id": "3c4821dc-b7ec-4a7f-a2c8-62adcc562ab8",
    "envelope_id": "3556acf6-f3a5-4214-8a74-07232ec66fb5",
    "amount": 50.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:36",
    "updated_at": "2024-08-31 22:47:04"
  },
  {
    "id": "3bbee43e-9034-4dd5-b154-b168b2339c6b",
    "envelope_id": "2a951bee-ba00-4edd-9f48-4cd400c3de72",
    "amount": 100.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:39",
    "updated_at": "2024-08-31 21:26:39"
  },
  {
    "id": "4bc13c17-e143-4fe6-a984-a7e5e8d2eb52",
    "envelope_id": "b35fa9c6-cd23-45cd-b8b6-970ff04ea39a",
    "amount": 50.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 21:26:43",
    "updated_at": "2024-08-31 21:32:40"
  },
  {
    "id": "7349dbfa-9fbd-4243-959d-49bdfaeefd6a",
    "envelope_id": "41e833e7-ad69-41da-8372-794065b33ef8",
    "amount": 2000.00,
    "period": "2024-08-01 00:00:00",
    "created_at": "2024-08-31 22:46:42",
    "updated_at": "2024-08-31 22:46:42"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);