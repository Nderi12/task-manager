<?php

namespace App\Forms\Manager;

use App\Forms\CommonForm;
use App\Models\Project;

class ProjectForm extends CommonForm
{
    public static function manage($input_data)
    {
        $form = new self($input_data);

        return $form->process();
    }
    public function setValidationRules()
    {
        $this->rules = [
            'id' => 'nullable|integer|exists:projects,id',
            'name' => 'required|max:255',
        ];
    }
    public function processInput()
    {
        if ($this->read('id', self::TYPE_INT)) {
            $project = Project::findOrFail($this->read('id', self::TYPE_INT));
        } else {
            $project = new Project();
        }

        $project->name = $this->read('name');

        $project->save();

        return $project;
    }
    public function getSuccessMessage($record)
    {
        return __('Project stored successfully.');
    }
}
