<?php

namespace App\Forms\Manager;

use App\Forms\CommonForm;
use App\Models\Task;

class TaskForm extends CommonForm
{
    public static function manage($input_data)
    {
        $form = new self($input_data);

        return $form->process();
    }
    public function setValidationRules()
    {
        $this->rules = [
            'id' => 'nullable|integer|exists:tasks,id',
            'name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'project_id' => 'required|integer|exists:projects,id',
        ];
    }
    public function processInput()
    {
        if ($this->read('id', self::TYPE_INT)) {
            $task = Task::findOrFail($this->read('id', self::TYPE_INT));
        } else {
            $task = new Task();
        }

        $task->name = $this->read('name');
        $task->priority = $this->read('priority');
        $task->project_id = $this->read('project_id');

        $task->save();

        return $task;
    }
    public function getSuccessMessage($record)
    {
        return __('Task stored successfully.');
    }
}
