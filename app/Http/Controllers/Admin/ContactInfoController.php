<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class ContactInfoController extends Controller
{
    use LogsAdminActions;

    public function index()
    {
        $contactInfo = [
            'address' => SiteSetting::get('contact_address', "Ministry Office\nGovernment District\nAccra, Ghana"),
            'email' => SiteSetting::get('contact_email', 'info@fatimatuabubakar.com'),
            'phone' => SiteSetting::get('contact_phone', '+233 123 456 789'),
            'hours' => SiteSetting::get('contact_hours', "Monday - Friday: 9:00 AM - 5:00 PM\nSaturday - Sunday: Closed"),
            'recipient_email' => SiteSetting::get('contact_recipient_email', config('mail.admin_email', env('ADMIN_EMAIL', 'admin@example.com'))),
        ];

        return view('admin.contact-info.index', compact('contactInfo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'contact_address' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_hours' => 'nullable|string|max:500',
            'contact_recipient_email' => 'required|email|max:255',
        ]);

        SiteSetting::set('contact_address', $validated['contact_address'] ?? '');
        SiteSetting::set('contact_email', $validated['contact_email'] ?? '');
        SiteSetting::set('contact_phone', $validated['contact_phone'] ?? '');
        SiteSetting::set('contact_hours', $validated['contact_hours'] ?? '');
        SiteSetting::set('contact_recipient_email', $validated['contact_recipient_email']);

        $this->logUpdate('ContactInfo', 0, ['updated' => 'contact_information']);

        return redirect()
            ->route('admin.contact-info.index')
            ->with('success', 'Contact information updated successfully.');
    }
}
