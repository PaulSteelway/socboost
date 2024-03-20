<?php
$memcached = new Memcached();
$memcached->addServer('localhost', 11211);

$memcached->set('test_key', 'test_value', 10);

echo $memcached->get('test_key');