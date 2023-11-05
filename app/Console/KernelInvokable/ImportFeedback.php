<?php

namespace App\Console\KernelInvokable;

use App\Models\Feedback;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ImportFeedback
{

    public function __invoke()
    {
        $disk = Storage::disk("imports");
        $filename = "Reviews+Import";
        $ext = ".csv";
        $filepath = $disk->path($filename.$ext);

        try {
            $handle = fopen($filepath, "r");
        } catch (Exception $e) {
            // no file found, skip this hourly import
            return true;
        }

        try {
            fgetcsv($handle); // skip first line as it contains the headers

            while ( ($row = fgetcsv($handle)) !== false ) {
                Feedback::query()->create([
                    'text' => $row[0],
                    'rating' => $row[1],
                    'start_date' => $row[2],
                    'address' => $row[3],
                    'appartments' => $row[4],
                    'source' => $row[5],
                ]);
            }
        } catch (Exception $e) {
            dd($e);
        }

        fclose($handle);
        try {
            // copy() is used for testing, using move() in production would be better to prevent duplicate entries
            // as there is no unique key on the feedback data
            if (App::environment("production")) {
                $disk->move($filename.$ext, "treated/$filename-" . date("Ymd-His") . $ext);
            } else {
                $disk->copy($filename.$ext, "treated/$filename-" . date("Ymd-His") . $ext);
            }
        } catch (Exception $exception) {
            dd($exception);
        }

        return true;
    }
}
