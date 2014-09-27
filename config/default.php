<?php

$array = json_decode(file_get_contents(__DIR__ . '/default.json'), true);

// Override db connection info
// $array['doctrine']['dbal']['default'] = [];

// Override admin login
// $array['rester']['auth']['basic']['user'] = 'go';
// $array['rester']['auth']['basic']['password'] = 'go';

return $array;
