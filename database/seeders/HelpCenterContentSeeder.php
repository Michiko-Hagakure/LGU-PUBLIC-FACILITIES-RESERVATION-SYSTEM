<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\HelpArticle;
use App\Models\News;
use Illuminate\Database\Seeder;

class HelpCenterContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFaqCategories();
        $this->seedFaqs();
        $this->seedHelpArticles();
        $this->seedNews();
    }

    private function seedFaqCategories(): void
    {
        $categories = [
            [
                'name' => 'Facility Reservations',
                'slug' => 'facility-reservations',
                'description' => 'Questions about booking and reserving public facilities',
                'icon' => 'calendar',
                'sort_order' => 1,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Payments & Fees',
                'slug' => 'payments-fees',
                'description' => 'Information about payment methods, fees, and billing',
                'icon' => 'credit-card',
                'sort_order' => 2,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Account Management',
                'slug' => 'account-management',
                'description' => 'Help with your account settings and profile',
                'icon' => 'user',
                'sort_order' => 3,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Cancellations & Refunds',
                'slug' => 'cancellations-refunds',
                'description' => 'Policies and procedures for cancellations and refund requests',
                'icon' => 'rotate-ccw',
                'sort_order' => 4,
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Facility Guidelines',
                'slug' => 'facility-guidelines',
                'description' => 'Rules, regulations, and usage guidelines for public facilities',
                'icon' => 'book-open',
                'sort_order' => 5,
                'is_active' => true,
                'created_by' => 1,
            ],
        ];

        foreach ($categories as $category) {
            FaqCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }

    private function seedFaqs(): void
    {
        $reservationsCategory = FaqCategory::where('slug', 'facility-reservations')->first();
        $paymentsCategory = FaqCategory::where('slug', 'payments-fees')->first();
        $accountCategory = FaqCategory::where('slug', 'account-management')->first();
        $cancellationsCategory = FaqCategory::where('slug', 'cancellations-refunds')->first();
        $guidelinesCategory = FaqCategory::where('slug', 'facility-guidelines')->first();

        $faqs = [
            // Facility Reservations
            [
                'category_id' => $reservationsCategory->id,
                'question' => 'How do I reserve a public facility?',
                'answer' => 'To reserve a facility, log in to your account and navigate to the "Browse Facilities" page. Select your preferred facility, choose an available date and time slot, fill out the booking form with the required details, and submit your reservation request. Your booking will be reviewed by staff before confirmation.',
                'sort_order' => 1,
                'is_published' => true,
                'view_count' => 45,
                'helpful_count' => 12,
                'created_by' => 1,
            ],
            [
                'category_id' => $reservationsCategory->id,
                'question' => 'How far in advance can I book a facility?',
                'answer' => 'You may submit a reservation request up to 90 days in advance. We recommend booking at least 2 weeks ahead for large events to ensure availability and allow sufficient time for staff verification and document processing.',
                'sort_order' => 2,
                'is_published' => true,
                'view_count' => 32,
                'helpful_count' => 8,
                'created_by' => 1,
            ],
            [
                'category_id' => $reservationsCategory->id,
                'question' => 'What documents do I need to submit with my reservation?',
                'answer' => 'You will need to provide a valid government-issued ID (front and back), a selfie with your ID for identity verification, and any supporting documents relevant to your event. For senior citizens, PWDs, or solo parents, please upload your corresponding discount ID for applicable rate reductions.',
                'sort_order' => 3,
                'is_published' => true,
                'view_count' => 28,
                'helpful_count' => 10,
                'created_by' => 1,
            ],
            [
                'category_id' => $reservationsCategory->id,
                'question' => 'How long does it take for my reservation to be approved?',
                'answer' => 'After submission, your booking undergoes staff verification within 1–3 business days. Once verified, you will receive a notification with your payment slip and instructions. After payment is confirmed by the City Treasurer\'s Office, your booking status will be updated to "Confirmed."',
                'sort_order' => 4,
                'is_published' => true,
                'view_count' => 38,
                'helpful_count' => 14,
                'created_by' => 1,
            ],
            [
                'category_id' => $reservationsCategory->id,
                'question' => 'Can I reserve multiple facilities for the same event?',
                'answer' => 'Yes, you may submit separate reservation requests for different facilities. Each booking is processed independently. Please ensure the schedules do not conflict and that each booking meets the respective facility requirements.',
                'sort_order' => 5,
                'is_published' => true,
                'view_count' => 15,
                'helpful_count' => 5,
                'created_by' => 1,
            ],

            // Payments & Fees
            [
                'category_id' => $paymentsCategory->id,
                'question' => 'What are the accepted payment methods?',
                'answer' => 'We accept payments through GCash, Maya (PayMaya), bank transfer, and over-the-counter payment at the City Treasurer\'s Office (CTO). You will receive detailed payment instructions along with your payment slip after your booking has been verified by staff.',
                'sort_order' => 1,
                'is_published' => true,
                'view_count' => 52,
                'helpful_count' => 18,
                'created_by' => 1,
            ],
            [
                'category_id' => $paymentsCategory->id,
                'question' => 'How much does it cost to reserve a facility?',
                'answer' => 'Rental rates vary depending on the facility, duration, and any additional equipment requested. City residents receive a discounted rate. You can view the specific rates on each facility\'s detail page before submitting your booking. The total amount, including any applicable discounts, will be reflected on your payment slip.',
                'sort_order' => 2,
                'is_published' => true,
                'view_count' => 41,
                'helpful_count' => 11,
                'created_by' => 1,
            ],
            [
                'category_id' => $paymentsCategory->id,
                'question' => 'What is the payment deadline after my booking is verified?',
                'answer' => 'You must complete payment within 72 hours (3 days) after receiving your payment slip. If payment is not received within this period, your reservation will automatically expire and the time slot will be released for other applicants.',
                'sort_order' => 3,
                'is_published' => true,
                'view_count' => 36,
                'helpful_count' => 9,
                'created_by' => 1,
            ],
            [
                'category_id' => $paymentsCategory->id,
                'question' => 'Do residents receive a discount on facility rentals?',
                'answer' => 'Yes, registered residents of the city are eligible for a resident discount on facility rental fees. Additionally, senior citizens, persons with disabilities (PWDs), and solo parents may qualify for further discounts upon presentation of a valid discount ID during the booking process.',
                'sort_order' => 4,
                'is_published' => true,
                'view_count' => 22,
                'helpful_count' => 7,
                'created_by' => 1,
            ],

            // Account Management
            [
                'category_id' => $accountCategory->id,
                'question' => 'How do I create an account?',
                'answer' => 'You can sign up using your Google account through our secure authentication system. Simply click "Sign in with Google" on the login page, and your account will be created automatically. Your name and email address will be imported from your Google profile.',
                'sort_order' => 1,
                'is_published' => true,
                'view_count' => 60,
                'helpful_count' => 20,
                'created_by' => 1,
            ],
            [
                'category_id' => $accountCategory->id,
                'question' => 'How can I update my profile information?',
                'answer' => 'Navigate to the "Profile" section from the sidebar menu. You can update your contact number, address, and other personal details. Some information linked to your Google account (such as your name and email) may require changes through your Google account settings.',
                'sort_order' => 2,
                'is_published' => true,
                'view_count' => 18,
                'helpful_count' => 6,
                'created_by' => 1,
            ],
            [
                'category_id' => $accountCategory->id,
                'question' => 'How do I check my booking history?',
                'answer' => 'Go to "Transaction History" in the sidebar to view all your past and current reservations. You can filter bookings by status (pending, confirmed, completed, etc.) and view details such as payment receipts, booking references, and facility information.',
                'sort_order' => 3,
                'is_published' => true,
                'view_count' => 25,
                'helpful_count' => 8,
                'created_by' => 1,
            ],

            // Cancellations & Refunds
            [
                'category_id' => $cancellationsCategory->id,
                'question' => 'Can I cancel my reservation?',
                'answer' => 'Yes, you may request cancellation of your booking. However, please note that cancellations made after payment verification may be subject to the city\'s refund policy. Contact the facility management office or submit a cancellation request through your booking details page.',
                'sort_order' => 1,
                'is_published' => true,
                'view_count' => 30,
                'helpful_count' => 10,
                'created_by' => 1,
            ],
            [
                'category_id' => $cancellationsCategory->id,
                'question' => 'How do I request a refund?',
                'answer' => 'If your booking is eligible for a refund, a refund request will be automatically generated upon cancellation or rejection. You will be asked to select your preferred refund method (GCash, bank transfer, or over-the-counter). Refund processing typically takes 5–10 business days depending on the selected method.',
                'sort_order' => 2,
                'is_published' => true,
                'view_count' => 27,
                'helpful_count' => 9,
                'created_by' => 1,
            ],
            [
                'category_id' => $cancellationsCategory->id,
                'question' => 'What happens if my booking is rejected?',
                'answer' => 'If your booking is rejected during the verification process, you will receive a notification explaining the reason. Common reasons include incomplete documentation, schedule conflicts, or facility unavailability. If you have already made payment, you will be eligible for a full refund.',
                'sort_order' => 3,
                'is_published' => true,
                'view_count' => 19,
                'helpful_count' => 6,
                'created_by' => 1,
            ],

            // Facility Guidelines
            [
                'category_id' => $guidelinesCategory->id,
                'question' => 'What are the general rules for using public facilities?',
                'answer' => 'All facility users must adhere to the following: arrive on time for your scheduled reservation, maintain cleanliness and orderliness, avoid bringing prohibited items (alcoholic beverages, hazardous materials), comply with the maximum capacity limit, and vacate the premises by the end of your reserved time slot. Any damage to the facility or equipment may result in additional charges.',
                'sort_order' => 1,
                'is_published' => true,
                'view_count' => 35,
                'helpful_count' => 12,
                'created_by' => 1,
            ],
            [
                'category_id' => $guidelinesCategory->id,
                'question' => 'Can I bring my own equipment to the facility?',
                'answer' => 'Yes, you may bring your own equipment such as sound systems, projectors, or decorations. However, all equipment must be declared during the booking process and is subject to staff approval. Electrical equipment must be in good working condition to prevent safety hazards.',
                'sort_order' => 2,
                'is_published' => true,
                'view_count' => 14,
                'helpful_count' => 4,
                'created_by' => 1,
            ],
            [
                'category_id' => $guidelinesCategory->id,
                'question' => 'Is there available parking at the facilities?',
                'answer' => 'Parking availability varies by facility. Most large venues such as the Main Conference Hall and Sports Complex have designated parking areas. Please check the specific facility details page for parking information. We recommend carpooling or using public transportation for events with large attendance.',
                'sort_order' => 3,
                'is_published' => true,
                'view_count' => 20,
                'helpful_count' => 7,
                'created_by' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }

    private function seedHelpArticles(): void
    {
        $articles = [
            [
                'title' => 'Getting Started: How to Reserve a Public Facility',
                'slug' => 'getting-started-reserve-facility',
                'excerpt' => 'A step-by-step guide to creating your first facility reservation through the online booking system.',
                'content' => '<h2>Step 1: Sign In to Your Account</h2><p>Visit the facility reservation portal and sign in using your Google account. If you are a first-time user, your account will be created automatically upon login.</p><h2>Step 2: Browse Available Facilities</h2><p>Navigate to the "Browse Facilities" page from the sidebar. You can view all available facilities along with photos, amenities, pricing, and available time slots. Use the calendar view to check availability for your preferred date.</p><h2>Step 3: Select a Facility and Time Slot</h2><p>Click on your preferred facility to view its details. Select your desired date and time slot from the availability calendar. The system will show you the applicable rates based on the duration and your residency status.</p><h2>Step 4: Complete the Booking Form</h2><p>Fill in the required information including event name, purpose, expected number of attendees, and any special requests. Upload the required documents: a valid government ID (front and back) and a selfie with your ID.</p><h2>Step 5: Review and Submit</h2><p>Review all your booking details, including the estimated total amount. Once submitted, your reservation will be queued for staff verification. You will receive a notification once your booking has been reviewed.</p><h2>Step 6: Make Payment</h2><p>After staff verification, you will receive a payment slip with the exact amount due and payment instructions. Complete payment within 72 hours using any of the accepted methods (GCash, Maya, bank transfer, or over-the-counter at the CTO).</p><h2>Step 7: Booking Confirmed</h2><p>Once your payment has been verified by the City Treasurer\'s Office, your booking status will be updated to "Confirmed" and you will receive an official receipt via email notification.</p>',
                'category' => 'booking',
                'sort_order' => 1,
                'is_published' => true,
                'view_count' => 78,
                'helpful_count' => 25,
                'tags' => json_encode(['reservation', 'booking', 'guide', 'getting started']),
                'created_by' => 1,
            ],
            [
                'title' => 'Understanding Payment Methods and Procedures',
                'slug' => 'payment-methods-procedures',
                'excerpt' => 'Learn about the different payment options available and how to complete your facility rental payment.',
                'content' => '<h2>Accepted Payment Methods</h2><p>The facility reservation system supports multiple payment channels for your convenience:</p><ul><li><strong>GCash</strong> — Send payment to the designated GCash number provided on your payment slip.</li><li><strong>Maya (PayMaya)</strong> — Transfer to the official Maya account listed on your payment slip.</li><li><strong>Bank Transfer</strong> — Deposit to the city government\'s official bank account. Details are included on your payment slip.</li><li><strong>Over-the-Counter (CTO)</strong> — Visit the City Treasurer\'s Office during business hours (Monday–Friday, 8:00 AM – 5:00 PM) and present your payment slip for processing.</li></ul><h2>Payment Timeline</h2><p>After your booking is verified by staff, you will have 72 hours to complete payment. A reminder notification will be sent 24 hours and 6 hours before the deadline. If payment is not received within this window, your reservation will expire automatically.</p><h2>Payment Verification</h2><p>For cashless payments, the City Treasurer\'s Office will verify your payment within 1–2 business days. Once verified, an Official Receipt (OR) will be issued and you will be notified via email and in-app notification.</p>',
                'category' => 'payment',
                'sort_order' => 2,
                'is_published' => true,
                'view_count' => 54,
                'helpful_count' => 17,
                'tags' => json_encode(['payment', 'gcash', 'maya', 'bank transfer', 'fees']),
                'created_by' => 1,
            ],
            [
                'title' => 'How to Check Facility Availability Using the Calendar',
                'slug' => 'check-facility-availability-calendar',
                'excerpt' => 'Use the calendar view to find open time slots and plan your reservation around existing bookings.',
                'content' => '<h2>Accessing the Calendar View</h2><p>From the sidebar, click on "Calendar View" under the Booking Management section. This displays a monthly overview of all facility bookings across all venues.</p><h2>Filtering by Facility</h2><p>Use the facility filter dropdown at the top of the calendar to narrow down the view to a specific venue. This is helpful when you are interested in a particular facility and want to see its availability at a glance.</p><h2>Reading the Calendar</h2><p>Color-coded events indicate different booking statuses:</p><ul><li><strong>Green</strong> — Confirmed bookings</li><li><strong>Yellow</strong> — Pending or under review</li><li><strong>Red</strong> — Rejected or cancelled</li><li><strong>Blue</strong> — Staff verified, awaiting payment</li></ul><h2>Checking Available Slots</h2><p>Click on any date to see the detailed schedule for that day. Available time slots will be clearly indicated. You can also click directly on a facility\'s detail page and use the built-in date picker to check availability for specific dates.</p>',
                'category' => 'booking',
                'sort_order' => 3,
                'is_published' => true,
                'view_count' => 33,
                'helpful_count' => 11,
                'tags' => json_encode(['calendar', 'availability', 'schedule', 'booking']),
                'created_by' => 1,
            ],
            [
                'title' => 'Managing Your Account and Profile Settings',
                'slug' => 'managing-account-profile',
                'excerpt' => 'Learn how to update your personal information, view transaction history, and manage notification preferences.',
                'content' => '<h2>Updating Personal Information</h2><p>Navigate to "Profile" in the sidebar to access your account settings. You can update your contact number, address, and other personal details. Your name and email are linked to your Google account and may need to be updated through Google\'s account settings.</p><h2>Viewing Transaction History</h2><p>Access "Transaction History" to see a complete record of all your bookings, including current status, payment details, and booking references. You can filter by date range and booking status for easier navigation.</p><h2>Payment History</h2><p>Under "Payment Methods," you can view all your payment transactions, including payment slips, official receipts, and refund records. Each transaction shows the payment method used, amount, and current verification status.</p><h2>Managing Notifications</h2><p>The notification bell icon in the top navigation bar shows your recent notifications. Click on any notification to view details. You can mark individual notifications as read or use "Mark All as Read" to clear them all at once.</p>',
                'category' => 'account',
                'sort_order' => 4,
                'is_published' => true,
                'view_count' => 22,
                'helpful_count' => 8,
                'tags' => json_encode(['account', 'profile', 'settings', 'notifications']),
                'created_by' => 1,
            ],
            [
                'title' => 'Cancellation and Refund Policies',
                'slug' => 'cancellation-refund-policies',
                'excerpt' => 'Understand the city\'s cancellation procedures and how refunds are processed for facility reservations.',
                'content' => '<h2>Cancellation Policy</h2><p>Reservations may be cancelled at any point before the scheduled event date. The refund amount depends on when the cancellation is made:</p><ul><li><strong>Before payment verification</strong> — Full refund, no processing required.</li><li><strong>After payment verification, 7+ days before event</strong> — Full refund of the rental fee.</li><li><strong>After payment verification, less than 7 days before event</strong> — Subject to review by the facility management office.</li></ul><h2>How to Request a Cancellation</h2><p>Go to your booking details page and click "Request Cancellation." Provide a reason for the cancellation. The request will be processed by the admin, and if a refund is applicable, you will be prompted to select your preferred refund method.</p><h2>Refund Methods</h2><p>Available refund methods include GCash, bank transfer, and over-the-counter collection at the City Treasurer\'s Office. Refunds are typically processed within 5–10 business days after approval.</p><h2>Tracking Your Refund</h2><p>You can track the status of your refund request under "My Refunds" in the sidebar. The system will notify you at each stage of the refund process.</p>',
                'category' => 'facility_info',
                'sort_order' => 5,
                'is_published' => true,
                'view_count' => 40,
                'helpful_count' => 13,
                'tags' => json_encode(['cancellation', 'refund', 'policy', 'guidelines']),
                'created_by' => 1,
            ],
            [
                'title' => 'Troubleshooting Common Booking Issues',
                'slug' => 'troubleshooting-booking-issues',
                'excerpt' => 'Solutions for common problems encountered during the reservation process.',
                'content' => '<h2>I Cannot See Available Time Slots</h2><p>If no time slots appear for your selected date, the facility may be fully booked or under maintenance. Try selecting a different date or check the bulletin board for any scheduled maintenance announcements.</p><h2>My Booking Was Rejected</h2><p>Bookings may be rejected due to incomplete documentation, schedule conflicts, or policy violations. Check the rejection reason in your notification and resubmit with the corrected information.</p><h2>I Did Not Receive My Payment Slip</h2><p>Payment slips are generated after staff verification. Check your notifications and email (including spam folder). If you still cannot find it, visit "Payment Methods" in the sidebar to view all your pending payment slips.</p><h2>My Payment Was Not Verified</h2><p>Payment verification by the City Treasurer\'s Office may take 1–2 business days. Ensure your payment reference matches the details on your payment slip. If verification is delayed beyond 3 business days, please contact the facility management office.</p><h2>The Calendar Shows My Slot as Available But I Cannot Book</h2><p>This may occur if another user is in the process of booking the same slot. The system holds time slots temporarily during the booking process. Please try again after a few minutes or select an alternative time slot.</p>',
                'category' => 'troubleshooting',
                'sort_order' => 6,
                'is_published' => true,
                'view_count' => 29,
                'helpful_count' => 9,
                'tags' => json_encode(['troubleshooting', 'issues', 'help', 'problems']),
                'created_by' => 1,
            ],
        ];

        foreach ($articles as $article) {
            HelpArticle::firstOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }

    private function seedNews(): void
    {
        $news = [
            [
                'title' => 'Online Facility Reservation System Now Available for All Residents',
                'slug' => 'online-facility-reservation-system-launch',
                'excerpt' => 'The city government is pleased to announce the official launch of the Online Public Facility Reservation System, allowing residents to conveniently book venues through a digital platform.',
                'content' => '<p>The Local Government Unit is proud to introduce the Online Public Facility Reservation System, a digital platform designed to streamline the booking process for community facilities across the city.</p><p>Through this system, residents can browse available facilities, view pricing and amenities, check real-time availability, and submit reservation requests — all from the comfort of their homes or offices.</p><h3>Key Features</h3><ul><li>Real-time facility availability and calendar view</li><li>Secure online payment through GCash, Maya, and bank transfer</li><li>Automated notifications for booking status updates</li><li>Digital payment slips and official receipts</li><li>Resident discount automatically applied for eligible users</li></ul><p>The system aims to reduce processing time, eliminate manual paperwork, and provide a transparent and efficient booking experience for all citizens.</p><p>For assistance, visit the Help Center or contact the facility management office during business hours.</p>',
                'category' => 'general',
                'is_published' => true,
                'is_featured' => true,
                'is_urgent' => false,
                'view_count' => 120,
                'tags' => json_encode(['launch', 'online booking', 'facilities']),
                'published_at' => now()->subDays(30),
                'created_by' => 1,
            ],
            [
                'title' => 'Main Conference Hall Renovation Complete — Now Accepting Reservations',
                'slug' => 'main-conference-hall-renovation-complete',
                'excerpt' => 'The recently renovated Main Conference Hall is now open for reservations with upgraded audio-visual equipment and improved seating capacity.',
                'content' => '<p>We are pleased to announce that the renovation of the Main Conference Hall has been completed. The facility is now open and accepting reservations through the online booking system.</p><h3>Upgrades Include</h3><ul><li>New high-definition projector and LED display panels</li><li>Upgraded wireless microphone and sound system</li><li>Improved air conditioning units for better ventilation</li><li>Expanded seating capacity from 150 to 200 persons</li><li>Newly installed Wi-Fi access for event attendees</li></ul><p>The renovated hall is ideal for seminars, workshops, community assemblies, and formal events. Residents are encouraged to book early as demand is expected to be high following the reopening.</p><p>Rental rates remain unchanged during the introductory period. Visit the facility details page for complete pricing and availability information.</p>',
                'category' => 'facility_update',
                'is_published' => true,
                'is_featured' => false,
                'is_urgent' => false,
                'view_count' => 85,
                'tags' => json_encode(['conference hall', 'renovation', 'facility update']),
                'published_at' => now()->subDays(15),
                'created_by' => 1,
            ],
            [
                'title' => 'Updated Facility Rental Rates Effective January 2026',
                'slug' => 'updated-facility-rental-rates-2026',
                'excerpt' => 'Please be informed of the revised rental rates for public facilities, effective January 1, 2026, as approved by the City Council.',
                'content' => '<p>In accordance with City Ordinance No. 2025-089, the rental rates for public facilities have been revised effective January 1, 2026. The updated rates reflect adjustments for maintenance costs and facility improvements.</p><h3>Summary of Changes</h3><ul><li>Base rental rates adjusted by 5–10% depending on the facility</li><li>Resident discount maintained at the current rate</li><li>Senior citizen, PWD, and solo parent discounts remain in effect</li><li>Extension rates standardized across all venues</li><li>Equipment rental fees updated to reflect current market rates</li></ul><p>The specific rates for each facility can be viewed on their respective detail pages in the booking system. All bookings made prior to January 1, 2026, will be honored at the previous rates.</p><p>For questions regarding the rate adjustments, please contact the City Treasurer\'s Office or visit the Help Center.</p>',
                'category' => 'policy_change',
                'is_published' => true,
                'is_featured' => false,
                'is_urgent' => false,
                'view_count' => 65,
                'tags' => json_encode(['rates', 'policy', 'pricing', 'update']),
                'published_at' => now()->subDays(45),
                'created_by' => 1,
            ],
            [
                'title' => 'Scheduled Maintenance: Sports Complex Closed February 10–14',
                'slug' => 'sports-complex-maintenance-february-2026',
                'excerpt' => 'The Sports Complex will be temporarily closed for scheduled maintenance from February 10 to 14, 2026. Existing reservations during this period will be rescheduled.',
                'content' => '<p>Please be advised that the Sports Complex will undergo scheduled preventive maintenance from <strong>February 10 to 14, 2026</strong>. The facility will be closed to the public during this period.</p><h3>Maintenance Activities</h3><ul><li>Floor resurfacing and repair of the indoor court</li><li>Inspection and servicing of electrical systems</li><li>Plumbing maintenance and restroom repairs</li><li>Repainting of common areas and exterior walls</li></ul><h3>Affected Reservations</h3><p>All existing reservations during the maintenance window have been identified and the affected applicants have been contacted individually. Options for rescheduling or refund have been provided.</p><p>We apologize for any inconvenience and appreciate your understanding as we work to maintain the quality and safety of our public facilities.</p><p>The facility is expected to reopen on <strong>February 15, 2026</strong>. Reservations for dates after the maintenance period are being accepted as usual.</p>',
                'category' => 'maintenance',
                'is_published' => true,
                'is_featured' => false,
                'is_urgent' => true,
                'view_count' => 92,
                'tags' => json_encode(['maintenance', 'sports complex', 'closure', 'schedule']),
                'published_at' => now()->subDays(5),
                'created_by' => 1,
            ],
            [
                'title' => 'GCash and Maya Payments Now Accepted for Facility Reservations',
                'slug' => 'gcash-maya-payments-accepted',
                'excerpt' => 'In line with the city\'s digital transformation initiative, cashless payment options via GCash and Maya are now available for facility rental payments.',
                'content' => '<p>The City Treasurer\'s Office, in partnership with the facility reservation management team, now accepts GCash and Maya as official payment channels for facility rental fees.</p><h3>How to Pay via GCash or Maya</h3><ol><li>Complete your booking and wait for staff verification.</li><li>Once verified, you will receive a payment slip with the GCash/Maya account details.</li><li>Send the exact amount indicated on your payment slip.</li><li>Use your booking reference number as the payment description.</li><li>The Treasurer\'s Office will verify your payment within 1–2 business days.</li></ol><h3>Benefits of Cashless Payment</h3><ul><li>No need to visit the City Treasurer\'s Office in person</li><li>Instant payment confirmation via transaction receipt</li><li>Secure and traceable digital transactions</li><li>Available 24/7 for your convenience</li></ul><p>Over-the-counter and bank transfer payment options remain available for those who prefer traditional methods.</p>',
                'category' => 'general',
                'is_published' => true,
                'is_featured' => false,
                'is_urgent' => false,
                'view_count' => 73,
                'tags' => json_encode(['gcash', 'maya', 'payment', 'cashless', 'digital']),
                'published_at' => now()->subDays(60),
                'created_by' => 1,
            ],
        ];

        foreach ($news as $item) {
            News::firstOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
