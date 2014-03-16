<?php

/**
 * Decorate the Invention class for the project plugin.
 * The project plugin adds a relationship between a project and an invention.
 */
class InventionDecorator_Project extends InventionDecorator implements iInvention {
    protected $decorated;

    /**
     * Get additional properties which will be applied to the Invention class
     *
     * @return array
     */
    protected function additionalProperties() {
        return array('project');
    }

    /**
     * Attach a project to this invention
     *
     * @param int $project_id, the id of the project
     * @return void
     */
    public function attachProject($project_id) {
        $this->decorated->project = $project_id;
    }
}
