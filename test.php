#!/usr/bin/env php
<?php
include "vendor/autoload.php";
$fw = new plibv4\streams\FileWriter("/root/test.bin", \plibv4\streams\FWMode::NEW);