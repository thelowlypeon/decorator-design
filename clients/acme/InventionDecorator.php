<?php

/**
 * Decorate the Invention class for the acme client.
 * For this example, the acme client wants to be able to detonate explosives.
 */
class InventionDecorator_Acme extends InventionDecorator implements iInvention {
    protected $decorated;

    /**
     * Get additional properties which will be applied to the Invention class
     *
     * @return array
     */
    protected function additionalProperties() {
        return array('has_explosives');
    }

    /**
     * Client specific method. If the invention has explosives, it goes BOOM.
     *
     * @return string
     */
    public function ignite() {
        if ($this->decorated->has_explosives) {
            return "!!! BOOM !!!";
        } else {
            return '~click~';
        }
    }
}
