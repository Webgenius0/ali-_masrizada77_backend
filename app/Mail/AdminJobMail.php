<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminJobMail extends Mailable {
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data) { $this->data = $data; }
    public function build() {
        $mail = $this->subject('New Job Application: ' . $this->data['first_name'])
                     ->view('mail.admin_job');

        if (isset($this->data['resume_path'])) {
            $mail->attach(public_path($this->data['resume_path']));
        }
        return $mail;
    }
}
