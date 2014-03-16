<?php

foreach (glob("framework/*.php") as $filename) {
    require_once($filename);
}
foreach (glob("base_models/*.php") as $filename) {
    require_once($filename);
}
foreach (glob('clients/' . CLIENT . '/*.php') as $filename) {
    require_once($filename);
}
foreach (glob("plugins/*/*.php") as $filename) {
    require_once($filename);
}


