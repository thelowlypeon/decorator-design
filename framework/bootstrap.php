<?php
/**
 * Bootstrap in the following order:
 *   1. Framework, include base models, decorators, etc
 *   2. Models that are loaded given any plugin combination
 *   3. The single client config
 *   4. All enabled plugins. (Note: in this app, all plugins are on.)
 */

foreach (glob("framework/*.php") as $filename) {
    require_once($filename);
}

foreach (glob("models/*.php") as $filename) {
    require_once($filename);
}

foreach (glob('clients/' . CLIENT . '/*.php') as $filename) {
    require_once($filename);
}

foreach (glob("plugins/*/*.php") as $filename) {
    require_once($filename);
}
