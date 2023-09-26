<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Job\AssignRequest;
use App\Http\Requests\Job\CreateJobRequest;
use App\Http\Requests\Job\UpdateJobAssignmentRequest;
use App\Manager\JobManager;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private JobManager $jobManager;

    public function __construct(JobManager $jobManager){


        $this->jobManager = $jobManager;
    }

    /**
     * Job List
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = $this->jobManager->index();
        $code = $result['code'];
        unset($result['code']);
        return response()->json($result, $code);
    }
    /**
     * Create job
     * @param CreateJobRequest $request
     * @return JsonResponse
     */
    public function store(CreateJobRequest $request): JsonResponse
    {
        $result = $this->jobManager->store(auth()->user(),$request->validated());
        $code = $result['code'];
        unset($result['code']);
        return response()->json($result, $code);
    }

    /**
     * Assign Job
     * @param AssignRequest $request
     * @return JsonResponse
     */
    public function assignJob(AssignRequest $request,Job $job): JsonResponse
    {
        $result = $this->jobManager->assignJob(auth()->user(),$job,$request->validated());
        $code = $result['code'];
        unset($result['code']);
        return response()->json($result, $code);
    }

    /**
     * Update Assignment Job
     * @param UpdateJobAssignmentRequest $request
     * @return JsonResponse
     */
    public function updateAssignmentJob(UpdateJobAssignmentRequest $request,Job $job): JsonResponse
    {
        $this->authorize('access_update',$job);
        $result = $this->jobManager->updateAssignmentJob(auth()->user(),$job,$request->validated());
        $code = $result['code'];
        unset($result['code']);
        return response()->json($result, $code);
    }

    /**
     * Delete Job
     * @return JsonResponse
     */
    public function delete(Job $job): JsonResponse
    {
        $this->authorize('access_delete',$job);
        $result = $this->jobManager->delete($job);
        $code = $result['code'];
        unset($result['code']);
        return response()->json($result, $code);
    }
}
