<?php

namespace App\Mails;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class FeedbackExport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $last_week_date;

    /**
     * @var string
     */
    protected $current_week_date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct() {
        $this->last_week_date = (new Carbon)->subDays(7)->hour(15)->minute(0)->format("Y-m-d H:i");
        $this->current_week_date = (new Carbon)->hour(15)->minute(0)->format("Y-m-d H:i");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): FeedbackExport
    {
        return $this->html("<p>Weekly report of imported feedback, from $this->last_week_date to $this->current_week_date</p>")->subject("Weekly feedback report");
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $json = Feedback::query()
            ->select(['text', 'rating', 'start_date', 'address', 'appartments', 'source'])
            ->whereBetween('created_at', [$this->last_week_date, $this->current_week_date])
            ->where('is_imported', true)
            ->get()
            ->toJson();

        return [
            Attachment::fromData(fn () => $json, 'report.json'),
        ];
    }
}
