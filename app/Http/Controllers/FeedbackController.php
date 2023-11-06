<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackImportRequest;
use App\Models\Feedback;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ImportFeedbackFile;

class FeedbackController extends Controller
{
    use ImportFeedbackFile;

    /**
     * Handle an incoming authentication request.
     */
    public function store(FeedbackImportRequest $request): RedirectResponse
    {
        if ($request->hasFile('feedback_file')) {
            $file = $request->validated('feedback_file');

            try {
                $this->importFeedbackFile($file->getPathname());
            } catch (Exception $e) {
                return Redirect::route('dashboard');
            }

            return Redirect::route('dashboard');
        } else if ($request->has('text')) {
            Feedback::query()->create([
                'text' => trim($request->validated('feedback_text')),
            ]);
        }

        return Redirect::route(auth()->check() ? 'dashboard' : 'home');
    }
}
