<?php
/**
 * Example Object, as well as what is needed to decorate it
 *
 * includes:
 *  * interface, which this object and all decorators MUST implement
 *  * Invention class definition, where you can add methods or default properties
 *  * abstract decorator class, which all decorators MUST extend
 *
 * Note that in a production app, these classes should probably belong in different files/places.
 */

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
