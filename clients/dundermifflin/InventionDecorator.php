<?php

class InventionDecorator_Client extends InventionDecorator implements iInvention {
    protected $decorated;

    protected function additionalProperties() {
        return array('paper_used');
    }

    public function paperUsed() {
        return "This invention used " . $this->decorated->paper_used . " sheets of paper";
    }
}
