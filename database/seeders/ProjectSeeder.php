<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $primeProject = Project::factory()->create([
            'name' => 'Prime'
        ]);

        $alphaProject = Project::factory()->create([
            'name' => 'Alpha'
        ]);

        // Prime project tasks
        Task::factory()->create([
            'name' => 'Prime task 1',
            'project_id' => $primeProject->id,
            'priority' => 1
        ]);
        Task::factory()->create([
            'name' => 'Prime task 2',
            'project_id' => $primeProject->id,
            'priority' => 2
        ]);
        Task::factory()->create([
            'name' => 'Prime task 3',
            'project_id' => $primeProject->id,
            'priority' => 3
        ]);

        // Alpha project tasks
        Task::factory()->create([
            'name' => 'Alpha task 1',
            'project_id' => $alphaProject->id,
            'priority' => 1
        ]);
        Task::factory()->create([
            'name' => 'Alpha task 2',
            'project_id' => $alphaProject->id,
            'priority' => 2
        ]);
        Task::factory()->create([
            'name' => 'Alpha task 3',
            'project_id' => $alphaProject->id,
            'priority' => 3
        ]);
    }
}
