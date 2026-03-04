<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Traits\HasCronLock;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class GenerateScheduledReportsCommand extends Command
{
    use HasCronLock;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umaep:generate-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate pending scheduled reports';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lockId = $this->acquireLock('generate_reports');
        if (!$lockId) {
            $this->warn('Job is already running. Skipping...');
            return 0;
        }

        try {
            $this->info('Checking for pending reports...');

            $pendingReports = Report::where('status', 'pending')->get();

            if ($pendingReports->isEmpty()) {
                $this->info('No reports to generate.');
                return 0;
            }

            $reportDir = storage_path('app/public/reports');
            if (!File::exists($reportDir)) {
                File::makeDirectory($reportDir, 0755, true);
            }

            foreach ($pendingReports as $report) {
                $this->info("Generating report: {$report->name} (Type: {$report->report_type})");

                try {
                    // Update status to processing
                    $report->update(['status' => 'processing']);

                    // Create mock CSV content
                    $filename = "report_{$report->id}_" . Carbon::now()->format('Y_m_d_His') . ".csv";
                    $filePath = "reports/" . $filename;
                    $absolutePath = $reportDir . DIRECTORY_SEPARATOR . $filename;

                    $csvContent = "ID,GeneratedAt,ReportType,Parameters\n";
                    $csvContent .= "{$report->id}," . Carbon::now()->toDateTimeString() . ",{$report->report_type}," . json_encode($report->parameters) . "\n";
                    $csvContent .= "Sample Data Row 1,Value A,Value B\n";
                    $csvContent .= "Sample Data Row 2,Value C,Value D\n";

                    File::put($absolutePath, $csvContent);

                    // Update report record
                    $report->update([
                        'status' => 'completed',
                        'file_path' => $filePath,
                        'completed_at' => Carbon::now(),
                    ]);

                    $this->info("Successfully generated: {$report->name}");
                }
                catch (\Exception $e) {
                    $this->error("Failed to generate report {$report->id}: " . $e->getMessage());
                    $report->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            $this->info('Report generation sweep complete.');
        }
        finally {
            $this->releaseLock('generate_reports', $lockId);
        }

        return 0;
    }
}
