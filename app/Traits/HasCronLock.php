<?php

namespace App\Traits;

use App\Models\CronJobLock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait HasCronLock
{
    /**
     * Attempt to acquire a lock for the job.
     *
     * @param string $jobName
     * @param int $timeout Minutes until lock expires
     * @return string|bool Lock ID if successful, false otherwise
     */
    protected function acquireLock(string $jobName, int $timeout = 60)
    {
        $lockId = Str::uuid()->toString();
        $now = Carbon::now();
        $expiresAt = $now->copy()->addMinutes($timeout);

        // Clean up expired locks for this job first
        CronJobLock::where('job_name', $jobName)
            ->where('expires_at', '<', $now)
            ->delete();

        // Check if a valid lock already exists
        if (CronJobLock::where('job_name', $jobName)->exists()) {
            return false;
        }

        try {
            CronJobLock::create([
                'job_name' => $jobName,
                'locked_at' => $now,
                'expires_at' => $expiresAt,
                'lock_id' => $lockId,
            ]);

            return $lockId;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Release the lock for the job.
     *
     * @param string $jobName
     * @param string $lockId
     * @return void
     */
    protected function releaseLock(string $jobName, string $lockId): void
    {
        CronJobLock::where('job_name', $jobName)
            ->where('lock_id', $lockId)
            ->delete();
    }
}
