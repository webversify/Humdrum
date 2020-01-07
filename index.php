<?php

/*
    HumDrum - The Monotonous CMS
    @package  HumDrum
    @file     index.php
    @author   Allan Celedonio <developer@webversify.com>
*/

//
// Load Native PHP Functions
//

session_start();
set_time_limit(0);

//
// Register Composer's Autoload
//

require __DIR__ . '/vendor/autoload.php';

//
// Initialize and Load Humdrum CMS
//

require __DIR__ . '/src/Init.php';

Initialize::HumDrum();
