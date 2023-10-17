<?php
return [
    "scan_paths" => [
        app_path(),
        resource_path('views'),
        resource_path('js'),
    ],
    "scan_pattern" => '/(@lang|__|\$t|\$tc)\s*(\(\s*[\'"])([^$]*)([\'"]+\s*(,[^\)]*)*\))/U',
    "lang_path" => './lang'
];