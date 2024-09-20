<?php

$data = <<<'JSON'
[
  {
    "envelope_id": "52d14a93-7a1f-4a21-9f0b-d93d03b03c8b",
    "tag_id": "4a534bab-cc85-4aac-a024-b0659d378da7"
  },
  {
    "envelope_id": "41e833e7-ad69-41da-8372-794065b33ef8",
    "tag_id": "91967a40-5d09-491d-a14d-bd31079e4846"
  },
  {
    "envelope_id": "0d0b3380-90bb-4b86-9c3e-f7e5695f1ede",
    "tag_id": "20323343-e63a-4006-bdd7-f0b7fa1526a5"
  },
  {
    "envelope_id": "000ce9dd-a298-4bc4-adc8-01b60d9ebcf0",
    "tag_id": "4a534bab-cc85-4aac-a024-b0659d378da7"
  },
  {
    "envelope_id": "c1bd1c41-673a-4a4b-818c-cc4015e30d92",
    "tag_id": "91967a40-5d09-491d-a14d-bd31079e4846"
  },
  {
    "envelope_id": "e4f8af2d-5619-41f4-8be8-9dcaa0d83f6a",
    "tag_id": "20323343-e63a-4006-bdd7-f0b7fa1526a5"
  }
]
JSON;

return json_decode($data, true);