<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'topic', 'message', 'read_at'])]
class Message extends Model
{
    use HasFactory;

    /**
     * The topics a visitor can pick on the Contact page, in the order the
     * select shows them.
     *
     * @var list<string>
     */
    public const TOPICS = [
        'Question about a plugin',
        'Help with a purchase or download',
        'Feature request',
        'Something else',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * Whether the admin has not opened this message yet.
     */
    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    /**
     * The Gmail compose link that answers the sender about their topic.
     */
    public function gmailReplyUrl(): string
    {
        return 'https://mail.google.com/mail/?view=cm&fs=1'
            .'&to='.rawurlencode($this->email)
            .'&su='.rawurlencode('Re: '.$this->topic);
    }

    /**
     * Limit the query to the messages nobody has read yet.
     */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }
}
