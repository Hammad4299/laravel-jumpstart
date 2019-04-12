<?php

function getDomainWithPort($url) {
    $domain = parse_url($url,PHP_URL_HOST);
    $port = parse_url($url,PHP_URL_PORT);
    if(!empty($port)) {
        return "$domain:$port";
    }
    return $domain;
}