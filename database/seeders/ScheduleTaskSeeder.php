<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ScheduleTask;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleTaskSeeder extends Seeder
{
    /**
     * Standard phase breakdown for new-build residential projects.
     * Weights are fractions of the total project duration.
     */
    private array $newBuildPhases = [
        ['name' => 'Securing Building Permit and Barangay Clearance', 'weight' => 0.08],
        ['name' => 'Site Clearing, Excavation, and Foundation Works', 'weight' => 0.12],
        ['name' => 'Structural Framing (Columns, Beams, and Slabs)', 'weight' => 0.20],
        ['name' => 'Masonry and CHB Block Laying', 'weight' => 0.15],
        ['name' => 'Roofing Installation (Trusses and G.I. Sheets)', 'weight' => 0.10],
        ['name' => 'Electrical Rough-in and Wiring', 'weight' => 0.08],
        ['name' => 'Plumbing Rough-in and Septic Tank Installation', 'weight' => 0.08],
        ['name' => 'Interior Partition, Ceiling, and Tile Flooring', 'weight' => 0.10],
        ['name' => 'Painting and Exterior Finishing', 'weight' => 0.06],
        ['name' => 'Final Inspection and Punch List Correction', 'weight' => 0.03],
    ];

    /**
     * Standard phase breakdown for renovation projects.
     */
    private array $renovationPhases = [
        ['name' => 'Site Assessment and Permit Application', 'weight' => 0.08],
        ['name' => 'Demolition and Debris Hauling', 'weight' => 0.12],
        ['name' => 'Roof Repair and Waterproofing', 'weight' => 0.15],
        ['name' => 'Electrical System Upgrade and Rewiring', 'weight' => 0.12],
        ['name' => 'Plumbing Line Improvement and Fixture Replacement', 'weight' => 0.12],
        ['name' => 'Wall Patching and Repainting', 'weight' => 0.15],
        ['name' => 'Flooring Replacement (Tiles/Vinyl)', 'weight' => 0.13],
        ['name' => 'Interior Remodeling and Fixture Installation', 'weight' => 0.13],
    ];

    /**
     * Pool of employee IDs to assign tasks from, loaded once per run.
     */
    private array $employeeIds = [];

    public function run(): void
    {
        // Fixed "today" reference so actual_start/actual_end dates line up
        // sensibly with each project's In progress / Planning status.
        $today = Carbon::parse('2026-08-26');

        $this->employeeIds = Employee::query()->pluck('id')->all();

        if (empty($this->employeeIds)) {
            $this->command?->warn('No employees found — run EmployeeSeeder before ScheduleTaskSeeder.');
            return;
        }

        Project::all()->each(function (Project $project) use ($today) {
            $this->seedForProject($project, $today);
        });
    }

    private function seedForProject(Project $project, Carbon $today): void
    {
        $isRenovation = $project->project_type === 'Renovation';
        $phases = $isRenovation ? $this->renovationPhases : $this->newBuildPhases;

        $start = Carbon::parse($project->start_date);
        $end = Carbon::parse($project->expected_end_date);
        $totalDays = max(1, $start->diffInDays($end));

        // Overall Summary task spanning the whole project
        ScheduleTask::create([
            'project_id' => $project->id,
            'name' => $project->name . ' - Full ' . ($isRenovation ? 'Renovation' : 'Construction') . ' Program',
            'task_type' => 'Summary',
            'planned_start_date' => $start->toDateString(),
            'planned_end_date' => $end->toDateString(),
            'actual_start_date' => $this->actualStart($project, $start, $today),
            'actual_end_date' => $this->actualEnd($project, $end, $today),
            'duration' => $this->formatDuration($start, $end),
            'assigned_employee_id' => $this->randomEmployeeId(),
        ]);

        // Kickoff milestone
        ScheduleTask::create([
            'project_id' => $project->id,
            'name' => $isRenovation ? 'Renovation Kickoff / Site Turnover' : 'Groundbreaking Ceremony',
            'task_type' => 'Milestone',
            'planned_start_date' => $start->toDateString(),
            'planned_end_date' => $start->toDateString(),
            'actual_start_date' => $this->actualStart($project, $start, $today),
            'actual_end_date' => $this->actualStart($project, $start, $today),
            'duration' => '1 day',
            'assigned_employee_id' => $this->randomEmployeeId(),
        ]);

        // Sequential phase tasks, chained one after another across the timeline
        $cursor = $start->copy();
        foreach ($phases as $phase) {
            $phaseDays = max(1, (int) round($totalDays * $phase['weight']));
            $phaseStart = $cursor->copy();
            $phaseEnd = $cursor->copy()->addDays($phaseDays - 1);

            ScheduleTask::create([
                'project_id' => $project->id,
                'name' => $phase['name'],
                'task_type' => 'Task',
                'planned_start_date' => $phaseStart->toDateString(),
                'planned_end_date' => $phaseEnd->toDateString(),
                'actual_start_date' => $this->actualStart($project, $phaseStart, $today),
                'actual_end_date' => $this->actualEnd($project, $phaseEnd, $today),
                'duration' => $this->formatDuration($phaseStart, $phaseEnd),
                'assigned_employee_id' => $this->randomEmployeeId(),
            ]);

            $cursor = $phaseEnd->copy()->addDay();
        }

        // Turnover milestone — only marked complete once the project record
        // itself has an actual_end_date (i.e. truly finished, not just past due)
        ScheduleTask::create([
            'project_id' => $project->id,
            'name' => 'Final Inspection and Turnover to Client',
            'task_type' => 'Milestone',
            'planned_start_date' => $end->toDateString(),
            'planned_end_date' => $end->toDateString(),
            'actual_start_date' => $project->actual_end_date ? $end->toDateString() : null,
            'actual_end_date' => $project->actual_end_date ? $end->toDateString() : null,
            'duration' => '1 day',
            'assigned_employee_id' => $this->randomEmployeeId(),
        ]);
    }

    /**
     * Pick a random employee ID from the loaded pool.
     */
    private function randomEmployeeId(): int
    {
        return $this->employeeIds[array_rand($this->employeeIds)];
    }

    /**
     * A task's actual_start_date is only set once its planned start has
     * already occurred, and only for projects that have actually begun work.
     */
    private function actualStart(Project $project, Carbon $plannedStart, Carbon $today): ?string
    {
        if ($project->status === 'Planning') {
            return null;
        }

        return $plannedStart->lte($today) ? $plannedStart->toDateString() : null;
    }

    /**
     * A task's actual_end_date is only set once its planned end has already
     * passed relative to today.
     */
    private function actualEnd(Project $project, Carbon $plannedEnd, Carbon $today): ?string
    {
        if ($project->status === 'Planning') {
            return null;
        }

        return $plannedEnd->lte($today) ? $plannedEnd->toDateString() : null;
    }

    /**
     * Turn a date range into a human-readable duration string,
     * e.g. "10 days", "3 weeks", "6 months", "1 year".
     */
    private function formatDuration(Carbon $start, Carbon $end): string
    {
        $days = max(1, $start->diffInDays($end) + 1);

        if ($days < 14) {
            return $days . ' day' . ($days === 1 ? '' : 's');
        }

        if ($days < 60) {
            $weeks = max(1, (int) round($days / 7));
            return $weeks . ' week' . ($weeks === 1 ? '' : 's');
        }

        if ($days < 365) {
            $months = max(1, (int) round($days / 30));
            return $months . ' month' . ($months === 1 ? '' : 's');
        }

        $years = intdiv($days, 365);
        $remainderMonths = (int) round(($days % 365) / 30);

        $result = $years . ' year' . ($years === 1 ? '' : 's');
        if ($remainderMonths > 0) {
            $result .= ' ' . $remainderMonths . ' month' . ($remainderMonths === 1 ? '' : 's');
        }

        return $result;
    }
}
