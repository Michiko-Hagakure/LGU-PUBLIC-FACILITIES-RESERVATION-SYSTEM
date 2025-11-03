<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\CitizenFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail; // Uncomment if mail is implemented
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Citizen\StoreCitizenQuestionRequest; // Requires you to create this file

class HelpFaqController extends Controller
{
    /**
     * Display the help and FAQ page for citizens.
     */
    public function index(): View
    {
        // FAQ data organized by category (kept as-is for presentation)
        $faqs = [
            [
                'category' => 'Getting Started',
                'questions' => [
                    [
                        'question' => 'How do I make my first facility reservation?',
                        'answer' => 'Click "New Reservation" from the sidebar, select your desired facility, choose an available date and time, fill out the booking form with event details, upload required documents, and submit your request.'
                    ],
                    [
                        'question' => 'What happens after I submit a reservation request?',
                        'answer' => 'Your request will be reviewed by staff who will verify your documents. You\'ll receive a notification once your booking is approved or if additional information is needed. Approved bookings will have a payment slip generated.'
                    ],
                    [
                        'question' => 'How long does the approval process take?',
                        'answer' => 'Most reservations are reviewed within 24-48 hours. Complex requests may take longer.'
                    ]
                ]
            ],
            [
                'category' => 'Payments and Fees',
                'questions' => [
                    [
                        'question' => 'How do I pay the facility fee?',
                        'answer' => 'Once your reservation is approved, a payment slip will be generated. You can pay through the designated payment channels mentioned on the slip, usually within 7 days.'
                    ],
                    [
                        'question' => 'What if I miss the payment deadline?',
                        'answer' => 'If payment is not received by the due date, your reservation may be automatically cancelled.'
                    ]
                ]
            ],
        ];

        return view('citizen.help-faq', compact('faqs'));
    }

    /**
     * Store a new question/feedback submitted by a citizen.
     * Uses StoreCitizenQuestionRequest for validation.
     */
    public function store(StoreCitizenQuestionRequest $request): RedirectResponse
    {
        // Validation is handled by StoreCitizenQuestionRequest
        $validated = $request->validated();
        
        try {
            // Save feedback to database
            $feedback = CitizenFeedback::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'category' => $validated['category'],
                'question' => $validated['question'],
                'status' => 'pending' // Initial status
            ]);

            // Log the submission
            Log::info('Citizen question submitted', [
                'id' => $feedback->id,
                'name' => $validated['name'],
                'category' => $validated['category']
            ]);

            // TODO: In a real system, send email notification to admin staff here
            // Mail::to('admin@lgu1.com')->send(new NewFeedbackMail($feedback));
            
            return redirect()
                ->route('citizen.help-faq')
                ->with('success', 'Your question has been submitted successfully! Our staff will respond to your email within 24 hours.');
                
        } catch (\Exception $e) {
            Log::error('Error submitting citizen question', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'There was an error submitting your question. Please try again or contact us directly.');
        }
    }
}