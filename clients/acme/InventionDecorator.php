<?php

class InventionDecorator_Client extends InventionDecorator implements iInvention {
    protected $decorated;

    protected function additionalProperties() {
        return array('has_explosives');
    }

    public function ignite() {
        if ($this->decorated->has_explosives) {
            return "!!! BOOM !!!";
        } else {
            return '~click~';
        }
    }
}
