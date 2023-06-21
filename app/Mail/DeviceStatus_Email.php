<?php

namespace App\Mail;

use App\Models\Device;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeviceStatus_Email extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $device;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user,Device $device)
    {
        $this->user=$user;
        $this->device=$device;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Device Status Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.device-mail',
            with: [
                'name' => $this->user->name,
                'device'=>$this->device->device_name,
            ],
        );
       
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
