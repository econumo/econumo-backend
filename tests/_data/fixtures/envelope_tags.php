<?php

$data = <<<'JSON'
[
  {
    "envelope_id": "3350ba95-6760-4c93-ab85-baa3c50ad6a4",
    "tag_id": "4b53d029-c1ed-46ad-8d86-1049542f4a7e"
  },
  {
    "envelope_id": "6960ea92-01cf-4737-91e9-2193cd2896c0",
    "tag_id": "e93abce1-0522-4a34-8dcf-5143769a3a8e"
  },
  {
    "envelope_id": "ccad84e4-6391-43b5-a7bd-a17b7622ad90",
    "tag_id": "4b53d029-c1ed-46ad-8d86-1049542f4a7e"
  },
  {
    "envelope_id": "13b50305-73a8-4eaf-97d2-dc006e4d87e0",
    "tag_id": "e93abce1-0522-4a34-8dcf-5143769a3a8e"
  },
  {
    "envelope_id": "d6aa80aa-3b18-4bbc-b1dd-c4f0d37d8c2f",
    "tag_id": "4b53d029-c1ed-46ad-8d86-1049542f4a7e"
  },
  {
    "envelope_id": "6153badb-50b7-494f-a54a-28422176dfd7",
    "tag_id": "e93abce1-0522-4a34-8dcf-5143769a3a8e"
  }
]
JSON;

return json_decode($data, true);