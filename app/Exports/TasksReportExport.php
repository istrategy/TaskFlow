<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TasksReportExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Project::with('tasks')->get()->map(function ($project) {
            $tasks = $project->tasks;
            $total = $tasks->count();
            $completed = $tasks->where('status', 'completed')->count();
            $pending = $tasks->where('status', 'pending')->count();
            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

            return [
                'project' => $project->title,
                'total_tasks' => $total,
                'completed' => $completed,
                'pending' => $pending,
                'completion_percentage' => $percentage . '%',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Project',
            'Total Tasks',
            'Completed',
            'Pending',
            'Completion %',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
