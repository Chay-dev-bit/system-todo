<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\WahaService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDailyTaskReminders extends Command
{
    protected $signature = 'todo:send-daily-task-reminders';

    protected $description = 'Kirim reminder WhatsApp jam 07:00 untuk task yang belum selesai.';

    public function handle(): int
    {
        $today = now()->timezone(config('app.timezone'))->startOfDay();

        $tasks = Task::query()
            ->with([
                'project',
                'assignee.pegawai',
            ])
            ->whereIn('status', ['pending', 'in_progress', 'rejected'])
            ->orderBy('deadline')
            ->orderBy('id')
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('Tidak ada task yang perlu diingatkan.');
            return self::SUCCESS;
        }

        $waha = new WahaService();

        $grouped = $tasks->groupBy('assigned_to');

        $sentCount = 0;
        foreach ($grouped as $assignedTo => $staffTasks) {
            $staff = $staffTasks->first()?->assignee;
            if (! $staff) {
                continue;
            }

            $phoneNumber = $staff->no_wa ?: ($staff->pegawai?->no_telp);
            if (! $phoneNumber) {
                continue;
            }

            $lines = [];
            $lines[] = 'Reminder Task - ' . $today->format('d-m-Y') . ' (07:00)';
            $lines[] = 'Halo ' . ($staff->nama_lengkap ?? 'Staff') . ', berikut task Anda yang belum selesai:';
            $lines[] = '';

            $maxItems = 10;
            foreach ($staffTasks->take($maxItems) as $index => $task) {
                $projectName = $task->project?->project_name ?? '-';
                $title = $task->title ?? '-';
                $status = strtoupper((string) $task->status);

                $targetText = 'Tanpa target';
                if (! empty($task->deadline)) {
                    $deadline = Carbon::parse($task->deadline)->startOfDay();
                    $diffDays = $today->diffInDays($deadline, false);

                    if ($diffDays < 0) {
                        $targetText = $deadline->format('d-m-Y') . ' (Terlambat ' . abs($diffDays) . ' hari)';
                    } elseif ($diffDays === 0) {
                        $targetText = $deadline->format('d-m-Y') . ' (Hari ini)';
                    } else {
                        $targetText = $deadline->format('d-m-Y') . ' (Sisa ' . $diffDays . ' hari)';
                    }
                }

                $lines[] = ($index + 1) . ') [' . $projectName . '] ' . $title;
                $lines[] = '   Status: ' . $status . ' | Target: ' . $targetText;
            }

            $remaining = $staffTasks->count() - $maxItems;
            if ($remaining > 0) {
                $lines[] = '';
                $lines[] = 'Dan ' . $remaining . ' task lainnya. Silakan cek detail di sistem.';
            }

            $message = implode("\n", $lines);

            $result = $waha->sendWhatsApp($phoneNumber, $message);
            if ($result !== false) {
                $sentCount++;
            }
        }

        $this->info('Selesai. Notifikasi terkirim ke ' . $sentCount . ' staff.');

        return self::SUCCESS;
    }
}

