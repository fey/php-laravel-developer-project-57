<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    TaskStatus,
    Task,
    Label,
    User,
};
use Symfony\Component\Yaml\Yaml;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(15)->create();

        $statuses =  Yaml::parseFile(database_path('task_statuses.yml'));
        foreach ($statuses as $status) {
            TaskStatus::firstOrCreate(['name' => $status]);
        }

        $labels = Yaml::parseFile(database_path('labels.yml'));
        foreach ($labels as $label) {
            Label::firstOrCreate([
                'name' => $label['name'],
                'description' => $label['description'],
            ]);
        }

        $tasks = Yaml::parseFile(database_path('tasks.yml'));
        foreach ($tasks as $task) {
            Task::firstOrCreate([
                'name' => $task['name'],
                'description' => $task['description'],
                'status_id' => TaskStatus::inRandomOrder()->first()->id,
                'created_by_id' => User::inRandomOrder()->first()->id,
                'assigned_to_id' => User::inRandomOrder()->first()->id,
            ]);
        }

        $labelsCount = Label::count();
        Task::all()->each(function ($task) use ($labelsCount) {
            $labels = Label::inRandomOrder()
                ->limit(rand(1, $labelsCount))
                ->get();

            $task->labels()->attach($labels);
        });
    }
}
