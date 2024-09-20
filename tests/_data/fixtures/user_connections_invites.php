<?php

$data = <<<'JSON'
[
  {
    "user_id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "code": null,
    "expired_at": null
  },
  {
    "user_id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "code": null,
    "expired_at": null
  },
  {
    "user_id": "2c652f2f-f958-40bc-8573-446f396f475d",
    "code": null,
    "expired_at": null
  },
  {
    "user_id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "code": null,
    "expired_at": null
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/\d{4}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


