<?php

namespace App\Http\Resources\Job;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $carbonDueDate = Carbon::parse($this->due_date)->timezone(auth()->user()->time_zone);
        return [
            'id' => $this->id,
            'user' => $this->user->name . " " . $this->user->surname,
            'due_date' => $carbonDueDate->format('Y-m-d H:i:s'),
            'assessment' => $this->assessment
        ];
    }
}
