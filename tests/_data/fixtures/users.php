<?php

$data = <<<'JSON'
[
  {
    "id": "ddcb3e96-2bab-4c48-a525-c3e0257f5b5b",
    "name": "John",
    "identifier": "john@econumo.demo",
    "password": "d4lJac1HOQhtVwBdm2xtxx2x9Csx/V4iLGxXVE3zonAQxMDXx2EWAcnu9Y0B4T2Lii9LHjEXJ77rJl1Q930prg==",
    "salt": "48f3a61def49c4a56b0394cd33dec5cca8db4254",
    "created_at": "2024-08-19 03:38:21",
    "updated_at": "2024-08-19 03:38:21"
  },
  {
    "id": "d60bf5c1-fb52-4389-8b64-45442584ac0e",
    "name": "Dany",
    "identifier": "dany@econumo.demo",
    "password": "wSUpfiLwtMBqar1MBqIoQHpsVvIBvrTET27Fxll0BRkaSlaYUMlQlIjkL+azMAgUum5fJH57SYIo9mTso8eHBw==",
    "salt": "b273a87a7881a985e1441e522e36243d866d0f5d",
    "created_at": "2024-09-02 22:19:46",
    "updated_at": "2024-09-02 22:19:46"
  },
  {
    "id": "2c652f2f-f958-40bc-8573-446f396f475d",
    "name": "Alex",
    "identifier": "alex@econumo.demo",
    "password": "eykWJBf9XRQdM6n1XdgF9nZpnJhCcfz55GzmS1rGtQkrDmYkW90EtXbi1IEW74EmHxdHww1WWWxDcQFXzqp0tA==",
    "salt": "25bd098c7d878c67a44cbedfa0723f47f394521d",
    "created_at": "2024-09-16 22:17:19",
    "updated_at": "2024-09-16 22:17:19"
  },
  {
    "id": "7ee550c4-019b-49c0-921d-d6a01d1ef143",
    "name": "Maria",
    "identifier": "maria@econumo.demo",
    "password": "HkwWrZBnWX+FZLco/lm+z70EQAkaZIAJy0gi7rkbRo+Bv29Vo8hCRpgV7ILcnPGGBRvgVRbYPfzpwWfOPZ/KUQ==",
    "salt": "a689024dd4617254718dc1118eb4d2aac6589e3a",
    "created_at": "2024-09-16 22:22:30",
    "updated_at": "2024-09-16 22:22:30"
  }
]
JSON;

$date = new DateTimeImmutable('-1 month');
$data = preg_replace('/2\d{3}-\d{2}-/', $date->format('Y-m-'), $data);

return json_decode($data, true);


