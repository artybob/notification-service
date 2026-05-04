<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function generateReport(int $userId, string $startDate, string $endDate): Report
    {
        return Report::create([
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => Report::STATUS_PENDING,
        ]);
    }

    public function processReport(Report $report): void
    {
        try {
            $report->update(['status' => Report::STATUS_PROCESSING]);

            // Собираем статистику
            $stats = Notification::where('user_id', $report->user_id)
                ->whereBetween('created_at', [$report->start_date, $report->end_date])
                ->select('channel', 'status', DB::raw('count(*) as count'))
                ->groupBy('channel', 'status')
                ->get();

            // Формируем данные для отчёта
            $channels = ['email', 'telegram'];
            $reportData = [];

            foreach ($channels as $channel) {
                $channelStats = $stats->where('channel', $channel);
                $reportData[$channel] = [
                    'total' => $channelStats->sum('count'),
                    'sent' => $channelStats->where('status', Notification::STATUS_SENT)->sum('count'),
                    'failed' => $channelStats->where('status', Notification::STATUS_FAILED)->sum('count'),
                ];
            }

            // Генерируем CSV
            $csvContent = $this->generateCsv($reportData);
            $fileName = "reports/report_{$report->user_id}_{$report->id}_{$report->created_at->timestamp}.csv";

            Storage::put($fileName, $csvContent);

            $report->update([
                'status' => Report::STATUS_READY,
                'file_path' => $fileName,
            ]);
        } catch (\Exception $e) {
            $report->update([
                'status' => Report::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function generateCsv(array $data): string
    {
        $csv = "Channel,Total,Sent,Failed\n";
        foreach ($data as $channel => $stats) {
            $csv .= "{$channel},{$stats['total']},{$stats['sent']},{$stats['failed']}\n";
        }

        return $csv;
    }
}
