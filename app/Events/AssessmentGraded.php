<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast as ShouldBroadcastContract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Submission;

class AssessmentGraded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Submission $submission;
    public int $score;

    /**
     * Create a new event instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
        $this->score = $submission->score ?? 0;
    }

    /**
     * Get the channels the event should broadcast on.
     * Broadcast to the user who submitted the assessment on a private channel.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->submission->user_id);
    }

    public function broadcastWith(): array
    {
        return [
            'submission_id' => $this->submission->id,
            'assessment_id' => $this->submission->assessment_id,
            'score' => $this->score,
            'graded_at' => $this->submission->graded_at?->toDateTimeString(),
        ];
    }
}
