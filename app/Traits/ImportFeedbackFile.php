<?php
namespace App\Traits;

use App\Models\Bdr;
use App\Models\Feedback;
use App\Models\Users\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rhinos\RsAdmin\Models\Order\Order;
use Rhinos\Rsmail\Api\Mail;
use Throwable;

trait ImportFeedbackFile
{
    public function importFeedbackFile($filepath): void
    {
        $handle = fopen($filepath, "r");

        fgetcsv($handle); // skip first line as it contains the headers

        while ( ($row = fgetcsv($handle)) !== false ) {
            if (!$row[0]) continue;

            Feedback::query()->create([
                'text' => trim($row[0]),
                'rating' => trim($row[1] ?? ''),
                'start_date' => trim($row[2] ?? ''),
                'address' => trim($row[3] ?? ''),
                'appartments' => trim($row[4] ?? ''),
                'source' => trim($row[5] ?? ''),
                'is_imported' => true,
            ]);
        }

        fclose($handle);
    }
}
