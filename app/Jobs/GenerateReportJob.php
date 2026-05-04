<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 2;

    public $backoff = [10, 30];

    protected Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function handle(ReportService $service): void
    {
        $service->processReport($this->report);
    }
}
