<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // ট্রানজেকশনের জন্য
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail; // <-- Mail ইম্পোর্ট করুন
use App\Mail\EventEntryCard; // <-- Mailable ইম্পোর্ট করুন
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class EventRegistrationController extends Controller
{
    // নতুন রেজিস্ট্রেশন সংরক্ষণ (POST /api/register-event)
    public function store(Request $request)
    {
        // 1. ডেটা ভ্যালিডেশন
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|unique:event_registrations|max:20',
            'email' => 'required|email|unique:event_registrations|max:255',
            'group_id' => 'required|integer|exists:groups,id', // গ্রুপ ID ভ্যালিডেশন
            'transaction_id' => 'nullable|string|max:255',
            'payment_status' => 'required|in:Pending,Paid,Waived',

            // Nested Members Validation
            'members' => 'required|array',
            'members.*.member_name' => 'required|string|max:255',
            'members.*.gender' => 'required|in:Male,Female,Child',
            'members.*.t_shirt_size' => 'nullable|string|max:10',
            'members.*.age' => 'nullable|integer|min:0',
        ]);

        // ডেটাবেস ট্রানজেকশন শুরু
        DB::beginTransaction();

        try {
            // 2. মূল রেজিস্ট্রেশন সেভ করা
            $registrationId = 'HF-' . strtoupper(Str::random(6));

            $registration = EventRegistration::create([
                'registration_id' => $registrationId,
                'name' => $validatedData['name'],
                'mobile' => $validatedData['mobile'],
                'email' => $validatedData['email'] ?? null,
                'group_id' => $validatedData['group_id'], // <--- গ্রুপ ID সেভ করা
                'total_members' => count($validatedData['members']), // মোট সদস্য সংখ্যা
                'transaction_id' => $validatedData['transaction_id'] ?? null,
                'payment_status' => $validatedData['payment_status'],
            ]);

            // 3. সদস্যদের ডেটা সেভ করা (Nested Save)
            foreach ($validatedData['members'] as $memberData) {
                $registration->members()->create($memberData);
            }

            // যদি সব সেভ সফল হয়
            DB::commit();

            // --- নতুন কোড: ইমেইল পাঠান ---
            try {
                Mail::to($registration->email)->send(new EventEntryCard($registration));
            } catch (\Exception $e) {
                // ইমেইল পাঠাতে ব্যর্থ হলেও মূল রেজিস্ট্রেশন যেন সফল থাকে
                // আপনি চাইলে এখানে লগ করতে পারেন
                // Log::error("Email sending failed: " . $e->getMessage());
            }
            // -----------------------------

            return response()->json([
                'message' => '✅ রেজিস্ট্রেশন ও সদস্য তালিকা সফলভাবে সম্পন্ন হয়েছে।',
                'registration' => $registration,
                'download_url' => route('registration.download', ['id' => $registration->registration_id]),
            ], 201);
        } catch (\Exception $e) {
            // কোনো ত্রুটি হলে রোলব্যাক
            DB::rollBack();

            return response()->json([
                'message' => '❌ ডেটাবেস সেভ করার সময় ত্রুটি হয়েছে।',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // রেজিস্ট্রেশন তালিকা (Protected Route)
    public function index()
    {
        $registrations = EventRegistration::with(['group', 'members'])->latest()->paginate(10);
        return response()->json($registrations);
    }

    // এন্ট্রি কার্ড ডাউনলোড (GET /api/registration/download/{id})
    public function downloadEntryCard($id)
{
    $registration = EventRegistration::with(['group', 'members'])
        ->where('registration_id', $id)
        ->firstOrFail();

    // Blade view কে HTML এ রেন্ডার করো
    $html = View::make('pdf.entry_card', compact('registration'))->render();

    // mPDF ইনিশিয়ালাইজ করো
    $mpdf = new Mpdf([
        'format' => 'A4',
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
    ]);

    // HTML লোড করো
    $mpdf->WriteHTML($html);

    // ফাইল নাম সেট করো
    $fileName = "Entry-Card-{$registration->registration_id}.pdf";

    // ব্রাউজারে ডাউনলোড করাও
    return response($mpdf->Output($fileName, 'I'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
}

    public function getStats(Request $request)
    {
        $totalRegistrations = EventRegistration::count();
        $totalMembers = EventRegistration::sum('total_members');

        // পেমেন্ট স্ট্যাটাস অনুযায়ী গণনা
        $paymentStats = EventRegistration::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->get()
            ->pluck('count', 'payment_status'); // 'Paid' => 10, 'Pending' => 5

        return response()->json([
            'total_registrations' => $totalRegistrations,
            'total_members' => (int) $totalMembers, // পূর্ণসংখ্যা হিসেবে পাঠান
            'total_paid' => $paymentStats->get('Paid', 0),
            'total_pending' => $paymentStats->get('Pending', 0),
            'total_waived' => $paymentStats->get('Waived', 0),
        ]);
    }
}
