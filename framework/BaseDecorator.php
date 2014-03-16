<?php
/**
 * The abstract base decorator class for all data object models.
 *
 * Classes wanting to extend a class should extend this and override properties/methods
 */
abstract class BaseDecorator {
    protected $decorated;
    public function properties() {$this->decorated->properties();}
    public function pre_save() {}
    public function post_save() {}
}
