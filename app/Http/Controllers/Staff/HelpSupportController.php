<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Staff\SubmitIssueRequest; // Requires you to create this file
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HelpSupportController extends Controller
{
    /**
     * Display the help and support page for staff.
     */
    public function index()
    {
        // FAQ data (can be moved to a configuration file or database)
        $faqs = [
            [
                'category' => 'Verification Process',
                'questions' => [
                    [
                        'question' => 'How do I verify a booking request?',
                        'answer' => 'Go to Document Verification, click on a pending booking, review all submitted documents, check for completeness and authenticity, then approve or reject based on the verification guidelines.'
                    ],
                    [
                        'question' => 'What documents are required for verification?',
                        'answer' => 'Standard requirements include: Valid Government ID, Barangay Certificate of Residency, Payment slip/proof of payment, Event permit (if applicable), and Authorized representative letter (if not the applicant).'
                    ],
                    [
                        'question' => 'How long should verification take?',
                        'answer' => 'Aim to complete verification within 24-48 hours of submission. Urgent or time-sensitive requests should be prioritized.'
                    ],
                ]
            ],
            [
                'category' => 'Document Handling',
                'questions' => [
                    [
                        'question' => 'What if a required document is missing?',
                        'answer' => 'You should reject the booking and include the rejection reason, specifically listing the missing documents. This sends a notification to the citizen.'
                    ],
                    [
                        'question' => 'How do I handle suspicious documents?',
                        'answer' => 'Immediately escalate to your supervisor and flag the booking for further investigation. Do not approve.'
                    ]
                ]
            ],
        ];
        
        // Contact details (can be moved to config/DB)
        $contacts = [
            [
                'title' => 'Admin Office',
                'name' => 'LGU1 Admin Department',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'admin@lgu1.com',
                'hours' => 'Mon-Fri, 8:00 AM - 5:00 PM'
            ],
            [
                'title' => 'Technical Support',
                'name' => 'IT Support Team',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'support@lgu1.com',
                'hours' => 'Mon-Fri, 8:00 AM - 5:00 PM'
            ],
            [
                'title' => 'Emergency Contact',
                'name' => 'Facility Manager',
                'phone' => '+63 XXX XXX XXXX',
                'email' => 'emergency@lgu1.com',
                'hours' => '24/7 Available'
            ],
        ];

        return view('staff.help-support', compact('faqs', 'contacts'));
    }

    /**
     * Submit a support ticket or issue report.
     * Uses SubmitIssueRequest for validation.
     */
    public function submitIssue(SubmitIssueRequest $request)
    {
        // Validation handled by SubmitIssueRequest
        $validated = $request->validated();
        
        $userId = Auth::id() ?? 'N/A';
        
        // Log the submitted issue for audit/tracking
        Log::info('Staff issue submitted', [
            'user_id' => $userId,
            'issue_type' => $validated['issue_type'],
            'subject' => $validated['subject'],
            'description_length' => strlen($validated['description']),
        ]);

        // In a real application, this would create a ticket/log entry in the database
        // and notify the appropriate IT/Maintenance team.

        return redirect()->route('staff.help-support')
            ->with('success', 'Your issue report has been submitted successfully. A ticket has been created.');
    }
}