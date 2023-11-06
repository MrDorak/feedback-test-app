<?php

namespace App\Console\KernelInvokable;

use App\Mails\FeedbackExport;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendFeedbackExportMail
{
    public function __invoke()
    {
        $admin_role_id = Role::where('alias', 'admin')->first()->id;
        $admin = User::where('role_id', $admin_role_id)->get();

        Mail::to($admin)->send(new FeedbackExport());
    }
}
