<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    // Patient Dashboard
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $appointments = $this->appointmentService->getPatientAppointments();

        return view('dashboard', compact('appointments'));
    }

    // Admin Dashboard View
    public function adminDashboard()
    {
        $appointments = $this->appointmentService->getAllAppointments();

        return view('admin.dashboard', compact('appointments'));
    }

    // Accept Appointment
    public function accept($id)
    {
        $this->appointmentService->acceptAppointment($id);

        return redirect()->back()->with(
            'success',
            'Appointment accepted successfully!'
        );
    }

    // Cancel Appointment with Reason
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|min:5',
        ]);

        $this->appointmentService->cancelAppointment(
            $id,
            $request->cancellation_reason
        );

        return redirect()->back()->with(
            'success',
            'Appointment canceled with reason recorded.'
        );
    }

    // Reschedule Appointment
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'reschedule_date_only' => 'required|date|after_or_equal:today',
            'reschedule_time_slot' => 'required|string',
        ]);

        $fullDateTime = Carbon::parse(
            $request->reschedule_date_only . ' ' . $request->reschedule_time_slot
        );

        if ($fullDateTime->isPast()) {
            return redirect()->back()->withErrors([
                'reschedule_time_slot' =>
                    'Cannot reschedule to a past time.',
            ]);
        }

        $this->appointmentService->rescheduleAppointment(
            $id,
            $request->reschedule_date_only,
            $request->reschedule_time_slot
        );

        return redirect()->back()->with(
            'success',
            'Appointment rescheduled successfully!'
        );
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

        $fullDateTime = Carbon::parse(
            $request->appointment_date_only . ' ' . $request->appointment_time_slot
        );

        if ($fullDateTime->isPast()) {
            return redirect()->back()
                ->withErrors([
                    'appointment_time_slot' =>
                        'You cannot book a time slot in the past.',
                ])
                ->withInput();
        }

        $this->appointmentService->createAppointment(
            $request->doctor_name,
            $request->appointment_date_only,
            $request->appointment_time_slot,
            $request->medical_notes
        );

        return redirect()->back()->with(
            'success',
            'Appointment scheduled successfully!'
        );
    }

    // View Prescriptions
    public function prescriptions()
    {
        $prescriptions =
            $this->appointmentService->getPatientPrescriptions();

        return view('prescriptions.index', compact('prescriptions'));
    }
}