<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Department;

class DepartmentAccessCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public Department $department;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Department $department)
    {
        $this->user = $user;
        $this->department = $department;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Department Access Code')
                    ->view('emails.department_access_code')
                    ->with([
                        'fullname' => $this->user->fullname,
                        'department' => $this->department,
                        'special_code' => $this->department->special_code,
                    ]);
    }
}
