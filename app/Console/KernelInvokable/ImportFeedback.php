<?php

namespace App\Console\KernelInvokable;

use App\Traits\ImportFeedbackFile;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ImportFeedback
{
    use ImportFeedbackFile;

    /**
     * @throws Exception
     */
    public function __invoke()
    {
        $disk = Storage::disk("imports");
        $filename = "Reviews+Import";
        $ext = ".csv";
        $filepath = $disk->path($filename.$ext);

        try {
            $this->importFeedbackFile($filepath);
        } catch (Exception $e) {
            throw new Exception($e);
        }

        try {
            // copy() is used for testing, using move() in production would be better to prevent duplicate entries
            // as there is no unique key on the feedback data
            if (App::environment("production")) {
                $disk->move($filename.$ext, "treated/$filename-" . date("Ymd-His") . $ext);
            } else {
                $disk->copy($filename.$ext, "treated/$filename-" . date("Ymd-His") . $ext);
            }
        } catch (Exception $exception) {
            throw new Exception($exception);
        }

        return true;
    }
}
