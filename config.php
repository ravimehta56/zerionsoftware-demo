<?php
// This file used to load Env variables and Defined Global Variables
(new DotEnv(__DIR__ . '/.env'))->load();


DEFINE('CLIENT_SECRET', getenv('CLIENT_SECRET'));
DEFINE('CLIENT_KEY', getenv('CLIENT_KEY'));
DEFINE('PAGE_ID', getenv('PAGE_ID'));
DEFINE('PROFILE_ID', getenv('PROFILE_ID'));
DEFINE('AUD_VALUE', "https://loadapp.iformbuilder.com/exzact/api/oauth/token");
DEFINE('RECORDURL',  "https://loadapp.iformbuilder.com/exzact/api/v60/profiles/" . PROFILE_ID . "/pages/" . PAGE_ID . "/records");
