<?php

class InventionDecorator_Project extends InventionDecorator implements iInvention {
    protected $decorated;

    protected function additionalProperties() {
        return array('project');
    }

    public function attachProject($project_id) {
        $this->decorated->project = $project_id;
    }
}
