<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AppointmentService
{
    /**
     * Get appointments belonging to the authenticated patient.
     */
    public function getPatientAppointments()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->orderBy('appointment_date', 'asc')
            ->get();

        foreach ($appointments as $appointment) {
            try {
                $appointment->medical_notes = Crypt::decryptString(
                    $appointment->medical_notes
                );
            } catch (\Exception $e) {
                // Fallback for unencrypted data
            }
        }

        return $appointments;
    }

    /**
     * Get all appointments for the admin dashboard.
     */
    public function getAllAppointments()
    {
        $appointments = Appointment::with('user')
            ->orderBy('appointment_date', 'asc')
            ->get();

        foreach ($appointments as $appointment) {
            try {
                $appointment->medical_notes = Crypt::decryptString(
                    $appointment->medical_notes
                );
            } catch (\Exception $e) {
                // Fallback for unencrypted data
            }
        }

        return $appointments;
    }

    /**
     * Accept an appointment.
     */
    public function acceptAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'status' => 'Accepted',
            'cancellation_reason' => null,
        ]);

        return $appointment;
    }

    /**
     * Cancel an appointment.
     */
    public function cancelAppointment($id, string $cancellationReason)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'status' => 'Canceled',
            'cancellation_reason' => $cancellationReason,
        ]);

        return $appointment;
    }

    /**
     * Reschedule an appointment.
     */
    public function rescheduleAppointment(
        $id,
        string $date,
        string $timeSlot
    ) {
        $fullDateTime = Carbon::parse($date . ' ' . $timeSlot);

        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'appointment_date' => $fullDateTime,
            'status' => 'Rescheduled',
            'cancellation_reason' => null,
        ]);

        return $appointment;
    }

    /**
     * Create a new patient appointment.
     */
    public function createAppointment(
        string $doctorName,
        string $date,
        string $timeSlot,
        string $medicalNotes
    ) {
        $fullDateTime = Carbon::parse($date . ' ' . $timeSlot);

        return Appointment::create([
            'user_id' => Auth::id(),
            'doctor_name' => $doctorName,
            'appointment_date' => $fullDateTime,
            'medical_notes' => Crypt::encryptString($medicalNotes),
            'status' => 'Pending',
        ]);
    }

    /**
     * Get prescriptions belonging to the authenticated patient.
     */
    public function getPatientPrescriptions()
    {
        return Prescription::where('user_id', Auth::id())
            ->orderBy('prescribed_date', 'desc')
            ->get();
    }
}