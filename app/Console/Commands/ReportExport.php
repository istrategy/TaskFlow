<?php

namespace App\Console\Commands;

use App\Exports\TasksReportExport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportExport extends Command
{
    protected $signature = 'report:export';

    protected $description = 'Export project task statistics to Excel file';

    public function handle(): int
    {
        $directory = 'reports';
        $filename = 'tasks_report' . '-' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        $path = $directory . '/' . $filename;

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        Excel::store(new TasksReportExport(), $path);

        $fullPath = storage_path('app/' . $path);

        $this->info('Report exported successfully!');
        $this->line('Location: ' . $fullPath);

        return Command::SUCCESS;
    }
}
