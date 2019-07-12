--TEST--
client cookie sharing disabled
--SKIPIF--
<?php
include "skipif.inc";
skip_client_test();
?>
--FILE--
<?php

include "helper/server.inc";
include "helper/dump.inc";

echo "Test\n";

server("cookie.inc", function($port) {
	$client = new http\Client(null, "cookies");
	$client->configure(array("share_cookies" => false));
	$request = new http\Client\Request("GET", "http://localhost:$port");
	$client->enqueue($request);
	$client->send();
	while (($r = $client->getResponse())) {
		dump_headers(null, $r->getHeaders());
	}
	/* requeue the previous request */
	$client->requeue($request);
	$request = new http\Client\Request("GET", "http://localhost:$port");
	$client->enqueue($request);
	$client->send();
	while (($r = $client->getResponse())) {
		dump_headers(null, $r->getHeaders());
	}
	$request = new http\Client\Request("GET", "http://localhost:$port");
	$client->enqueue($request);
	$client->send();
	while (($r = $client->getResponse())) {
		dump_headers(null, $r->getHeaders());
	}
	$request = new http\Client\Request("GET", "http://localhost:$port");
	$client->enqueue($request);
	$client->send();
	while (($r = $client->getResponse())) {
		dump_headers(null, $r->getHeaders());
	}
});

?>
===DONE===
--EXPECTF--
Test
Etag: ""
Set-Cookie: counter=1;
X-Original-Transfer-Encoding: chunked

Etag: ""
Set-Cookie: counter=1;
X-Original-Transfer-Encoding: chunked

Etag: ""
Set-Cookie: counter=2;
X-Original-Transfer-Encoding: chunked

Etag: ""
Set-Cookie: counter=1;
X-Original-Transfer-Encoding: chunked

Etag: ""
Set-Cookie: counter=1;
X-Original-Transfer-Encoding: chunked

===DONE===
