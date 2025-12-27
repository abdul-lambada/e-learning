<?php

namespace App\Notifications;

use App\Models\ForumBalasan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForumReplied extends Notification
{
    use Queueable;

    protected $balasan;

    public function __construct(ForumBalasan $balasan)
    {
        $this->balasan = $balasan;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'forum',
            'title' => 'Balasan Baru di Forum',
            'message' => $this->balasan->user->nama_lengkap . ' membalas topik: ' . $this->balasan->topik->judul,
            'url' => route('forum.show', $this->balasan->topik->id),
            'icon' => 'bx bx-chat',
            'color' => 'primary',
        ];
    }
}
