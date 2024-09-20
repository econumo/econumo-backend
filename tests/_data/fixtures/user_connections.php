<?php

$data = <<<'JSON'
[
  {
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "connected_user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e"
  },
  {
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "connected_user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


