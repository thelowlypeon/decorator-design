<?php

/**
 * Decorate the Invention class for the dundermifflin client.
 * For this example, the dunder mifflin client wants to track paper used by invention.
 */
class InventionDecorator_Client extends InventionDecorator implements iInvention {
    protected $decorated;

    /**
     * Get additional properties which will be applied to the Invention class
     *
     * @return array
     */
    protected function additionalProperties() {
        return array('paper_used');
    }

    /**
     * Client specific method. Get the number of sheets of paper used on this invention
     *
     * @return string
     */
    public function paperUsed() {
        return "This invention used " . $this->decorated->paper_used . " sheets of paper";
    }
}
