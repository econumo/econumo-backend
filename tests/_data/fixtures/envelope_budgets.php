<?php

$data = <<<'JSON'
[
  {
    "id": "25981b61-332f-4908-aec0-6c42e29a5549",
    "envelope_id": "10d26248-9518-46eb-84ca-6ab8d4cbcdbb",
    "amount": 10000.00,
    "period": "2019-12-01 00:00:00",
    "created_at": "2023-10-22 04:57:46",
    "updated_at": "2023-10-22 04:57:46"
  },
  {
    "id": "50cad6ac-073b-41f2-b694-f8f1d4535d78",
    "envelope_id": "96ab006d-4f9d-43e1-abfc-18151e9c59d7",
    "amount": 1000.00,
    "period": "2019-12-01 00:00:00",
    "created_at": "2023-10-22 04:57:54",
    "updated_at": "2023-10-22 04:57:54"
  }
]
JSON;

return json_decode($data, true);