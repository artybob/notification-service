<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateReportJob;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = $this->reportService->generateReport(
            $validated['user_id'],
            $validated['start_date'],
            $validated['end_date']
        );

        GenerateReportJob::dispatch($report);

        return response()->json([
            'report_id' => $report->id,
            'status' => Report::STATUS_PENDING,
        ], 202);
    }

    public function status(int $reportId): JsonResponse
    {
        $report = Report::findOrFail($reportId);

        return response()->json([
            'id' => $report->id,
            'status' => $report->status,
            'file_url' => $report->status === Report::STATUS_READY
                ? route('reports.download', $report->id)
                : null,
        ]);
    }

    public function download(int $reportId)
    {
        $report = Report::findOrFail($reportId);

        if ($report->status !== Report::STATUS_READY || ! $report->file_path) {
            abort(404, 'Report not ready');
        }

        if (! Storage::exists($report->file_path)) {
            abort(404, 'Report file not found');
        }

        return Storage::download($report->file_path, "report_{$report->id}.csv");
    }
}
