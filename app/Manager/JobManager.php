<?php

namespace App\Manager;

use App\Http\Resources\Job\ViewJobsResource;
use App\Models\Job;
use App\Models\JobAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class JobManager
{

    /**
     * Job List
     */
    public function index()
    {
        $jobs = Job::all();
        return ['success' => true, 'message' => 'Success', 'code' => 200, 'data' => ViewJobsResource::collection($jobs)];
    }

    /**
     * Create Job
     */
    public function store(User $user, array $requestData)
    {
        try {
            Job::create([
                "title" => $requestData['title'],
                "description" => $requestData['description'],
                "priority" => $requestData['priority'],
                "created_by" => $user->id,
            ]);
            Log::info("Successfully created job with title: {}");

            return ['success' => true, 'message' => 'Success', 'code' => 200, 'data' => []];
        } catch (\Exception $exception) {
            Log::error("Error creating job {$exception->getMessage()}");
            return ['success' => false, 'message' => 'Error', 'code' => 402, 'data' => []];

        }
    }

    /**
     * Update Job
     */
    public function assignJob(User $user, Job $job, array $requestData)
    {
        try {
            JobAssignment::create([
                "job_id" => $job->id,
                "user_id" => $user->id,
                "due_date" => $requestData['due_date'],
            ]);
            Log::info("Successfully assign job");

            return ['success' => true, 'message' => 'Success', 'code' => 200, 'data' => []];
        } catch (\Exception $exception) {
            Log::error("Error assign job {$exception->getMessage()}");
            return ['success' => false, 'message' => 'Error', 'code' => 402, 'data' => []];

        }
    }

    /**
     * Update Job
     */
    public function updateAssignmentJob(User $user, Job $job, array $requestData)
    {
        try {
            $assignmentJob = $job->jobs_assignment()->where('user_id', $user->id)->first();

            $assignmentJob->update([
                "completed" => $requestData['completed'],
                "assessment" => $requestData['assessment'],
            ]);
            Log::info("Successfully updated");

            return ['success' => true, 'message' => 'Success', 'code' => 200, 'data' => []];
        } catch (\Exception $exception) {
            Log::error("Error update job {$exception->getMessage()}");
            return ['success' => false, 'message' => 'Error', 'code' => 402, 'data' => []];

        }
    }
}
