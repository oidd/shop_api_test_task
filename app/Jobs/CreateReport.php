<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Notifications\SendReportCreatedNotification;
use App\Service\Reports\ReportService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class CreateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $name,
        public Admin $admin,
        public $from = null,
        public $to = null,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(ReportService $reportService): void
    {
        if ($this->from === null && $this->to === null) {
            $this->from = Carbon::createFromTimestamp(0);
            $this->to = Carbon::createFromTimestamp(Carbon::now()->timestamp);
        }
        elseif ($this->from === null) {
            $this->from = Carbon::createFromTimestamp(0);
        }
        elseif ($this->to === null) {
            $this->to = Carbon::createFromTimestamp(Carbon::now()->timestamp);
        }

        $report = $reportService->reportForDateRange(
            $this->from,
            $this->to
        );

        $report->save(
            'storage/app/reports/' . $this->name
        );

        Notification::send(
            $this->admin,
            new SendReportCreatedNotification($this->name)
        );
    }
}
