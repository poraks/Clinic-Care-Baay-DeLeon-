<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AppointmentController extends Controller
{
    // Patient Dashboard
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $appointments = Appointment::where('user_id', Auth::id())->orderBy('appointment_date', 'asc')->get();

        foreach ($appointments as $appointment) {
            try {
                $appointment->medical_notes = Crypt::decryptString($appointment->medical_notes);
            } catch (\Exception $e) {
                // Fallback for unencrypted data
            }
        }

        return view('dashboard', compact('appointments'));
    }

    // Admin Dashboard View
    public function adminDashboard()
    {
        $appointments = Appointment::with('user')->orderBy('appointment_date', 'asc')->get();

        foreach ($appointments as $appointment) {
            try {
                $appointment->medical_notes = Crypt::decryptString($appointment->medical_notes);
            } catch (\Exception $e) {
                // Fallback
            }
        }

        return view('admin.dashboard', compact('appointments'));
    }

    // Accept Appointment
    public function accept($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'status' => 'Accepted',
            'cancellation_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Appointment accepted successfully!');
    }

    // Cancel Appointment with Reason
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|min:5',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'status' => 'Canceled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->back()->with('success', 'Appointment canceled with reason recorded.');
    }

    // Reschedule Appointment
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'reschedule_date_only' => 'required|date|after_or_equal:today',
            'reschedule_time_slot' => 'required|string',
        ]);

        $fullDateTime = Carbon::parse($request->reschedule_date_only . ' ' . $request->reschedule_time_slot);

        if ($fullDateTime->isPast()) {
            return redirect()->back()->withErrors(['reschedule_time_slot' => 'Cannot reschedule to a past time.']);
        }

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'appointment_date' => $fullDateTime,
            'status' => 'Rescheduled',
            'cancellation_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Appointment rescheduled successfully!');
    }

    // Store New Patient Appointment
    public function store(Request $request)
    {
        $request->validate([
            'doctor_name' => 'required|string|in:Dr. Jose Rizal,Dr. Emilio Aguinaldo,Dr. Kyve Calimpusan',
            'appointment_date_only' => 'required|date|after_or_equal:today',
            'appointment_time_slot' => 'required|string',
            'medical_notes' => 'required|string|min:5',
        ]);

        $fullDateTime = Carbon::parse($request->appointment_date_only . ' ' . $request->appointment_time_slot);

        if ($fullDateTime->isPast()) {
            return redirect()->back()->withErrors(['appointment_time_slot' => 'You cannot book a time slot in the past.'])->withInput();
        }

        Appointment::create([
            'user_id' => Auth::id(),
            'doctor_name' => $request->doctor_name,
            'appointment_date' => $fullDateTime,
            'medical_notes' => Crypt::encryptString($request->medical_notes),
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Appointment scheduled successfully!');
    }

    // View Prescriptions
    public function prescriptions()
    {
        $prescriptions = Prescription::where('user_id', Auth::id())->orderBy('prescribed_date', 'desc')->get();
        return view('prescriptions.index', compact('prescriptions'));
    }
}