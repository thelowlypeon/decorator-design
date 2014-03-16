<?php

abstract class BaseDecorator {
    protected $decorated;
    public function properties() {$this->decorated->properties();}
    public function pre_save() {}
    public function post_save() {}
}
