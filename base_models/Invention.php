<?php

interface iInvention {
    public function pre_save();
    public function post_save();
    public function properties();
}

class Invention extends BaseModel implements iInvention {
    public function defaultProperties() {
        return array('inventors');
    }
}

abstract class InventionDecorator extends BaseDecorator implements iInvention {
    public function __construct(iInvention $decorated) {
        $this->decorated = $decorated;
        $this->_addProperties();
    }

    protected function _addProperties() {
        if ($additional_properties = $this->additionalProperties()) {
            return $this->decorated->addProperties($additional_properties);
        }
        return $this->properties();
    }
}
