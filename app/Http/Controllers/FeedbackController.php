<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackImportRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Feedback;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class FeedbackController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(FeedbackImportRequest $request): RedirectResponse
    {
        $file = $request->validated('file');

        try {
            $handle = fopen($file->getPathname(), "r");
        } catch (Exception $e) {
            return Redirect::route('dashboard');
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
            return Redirect::route('dashboard');
        }

        fclose($handle);

        return Redirect::route('dashboard');
    }
}
