<x-app-layout>
    <x-slot name="header">
        Admin Management — Appointments Overview
    </x-slot>

    <style>
        .admin-card { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 24px; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 16px; text-align: left; }
        .admin-table th { background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 16px; }
        .admin-table td { border-bottom: 1px solid #f1f5f9; padding: 16px; font-size: 13px; color: #1e293b; }
        
        .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .status-accepted { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .status-canceled { background-color: #ffe4e6; color: #be123c; border: 1px solid #fecdd3; }
        .status-rescheduled { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-pending { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }

        .btn-action-accept { background-color: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-action-accept:hover { background-color: #059669; }
        
        .btn-action-cancel { background-color: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-action-cancel:hover { background-color: #dc2626; }

        .btn-action-reschedule { background-color: #2563eb; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-action-reschedule:hover { background-color: #1d4ed8; }

        .text-locked { font-size: 11px; font-weight: 700; color: #94a3b8; font-style: italic; }
    </style>

    <div class="admin-card">
        <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 16px;">
            <h2 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">All Scheduled Patient Bookings</h2>
            <p style="font-size: 12px; color: #64748b; margin-top: 4px;">Review, approve, cancel with reasons, or reschedule patient bookings.</p>
        </div>

        @if (session('success'))
            <div style="padding: 12px 16px; background-color: #dcfce7; border: 1px solid #86efac; color: #166534; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 16px;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($appointments->isEmpty())
            <p style="text-align: center; color: #94a3b8; padding: 32px 0; font-size: 13px;">No patient appointment bookings available.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Assigned Physician</th>
                        <th>Scheduled Date & Time</th>
                        <th>Medical Notes</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $item)
                        <tr>
                            <td style="font-weight: 700; color: #0f172a;">{{ $item->user->name ?? 'N/A' }}</td>
                            <td style="font-weight: 600; color: #334155;">{{ $item->doctor_name }}</td>
                            <td style="font-weight: 600; color: #2563eb;">{{ \Carbon\Carbon::parse($item->appointment_date)->format('M d, Y - h:i A') }}</td>
                            <td style="color: #475569; max-width: 200px;">{{ $item->medical_notes }}</td>
                            <td>
                                @if($item->status === 'Accepted')
                                    <span class="status-pill status-accepted">Accepted</span>
                                @elseif($item->status === 'Canceled')
                                    <span class="status-pill status-canceled">Canceled</span>
                                    @if($item->cancellation_reason)
                                        <div style="font-size: 10px; color: #e11d48; margin-top: 4px;">Reason: {{ $item->cancellation_reason }}</div>
                                    @endif
                                @elseif($item->status === 'Rescheduled')
                                    <span class="status-pill status-rescheduled">Rescheduled</span>
                                @else
                                    <span class="status-pill status-pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'Accepted')
                                    <!-- LOCKED AFTER ACCEPTING -->
                                    <span class="text-locked">No further actions available</span>
                                @elseif($item->status === 'Canceled')
                                    <!-- LOCKED AFTER CANCELING -->
                                    <span class="text-locked">Appointment Canceled</span>
                                @else
                                    <!-- ACTIONS AVAILABLE FOR PENDING OR RESCHEDULED APPOINTMENTS -->
                                    <div style="display: flex; gap: 6px; align-items: center;">
                                        <form method="POST" action="{{ route('admin.appointments.accept', $item->id) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action-accept">Accept</button>
                                        </form>

                                        <details style="position: relative; display: inline-block;">
                                            <summary class="btn-action-cancel" style="list-style: none;">Cancel</summary>
                                            <form method="POST" action="{{ route('admin.appointments.cancel', $item->id) }}" style="position: absolute; right: 0; top: 100%; margin-top: 6px; width: 220px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 50;">
                                                @csrf
                                                @method('PATCH')
                                                <label style="font-size: 11px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Reason for Cancellation:</label>
                                                <input type="text" name="cancellation_reason" required placeholder="Required..." style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px; font-size: 12px; margin-bottom: 8px;">
                                                <button type="submit" class="btn-action-cancel" style="width: 100%;">Confirm Cancel</button>
                                            </form>
                                        </details>

                                        <details style="position: relative; display: inline-block;">
                                            <summary class="btn-action-reschedule" style="list-style: none;">Reschedule</summary>
                                            <form method="POST" action="{{ route('admin.appointments.reschedule', $item->id) }}" style="position: absolute; right: 0; top: 100%; margin-top: 6px; width: 220px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 50;">
                                                @csrf
                                                @method('PATCH')
                                                <label style="font-size: 11px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">New Date:</label>
                                                <input type="date" name="reschedule_date_only" min="{{ date('Y-m-d') }}" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px; font-size: 12px; margin-bottom: 8px;">
                                                
                                                <label style="font-size: 11px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">New Time Slot:</label>
                                                <select name="reschedule_time_slot" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px; font-size: 12px; margin-bottom: 8px;">
                                                    <option value="08:00">08:00 AM</option>
                                                    <option value="08:30">08:30 AM</option>
                                                    <option value="09:00">09:00 AM</option>
                                                    <option value="09:30">09:30 AM</option>
                                                    <option value="10:00">10:00 AM</option>
                                                    <option value="10:30">10:30 AM</option>
                                                    <option value="11:00">11:00 AM</option>
                                                    <option value="11:30">11:30 AM</option>
                                                    <option value="12:00">12:00 PM</option>
                                                    <option value="12:30">12:30 PM</option>
                                                    <option value="13:00">01:00 PM</option>
                                                    <option value="13:30">01:30 PM</option>
                                                    <option value="14:00">02:00 PM</option>
                                                    <option value="14:30">02:30 PM</option>
                                                    <option value="15:00">03:00 PM</option>
                                                    <option value="15:30">03:30 PM</option>
                                                    <option value="16:00">04:00 PM</option>
                                                    <option value="16:30">04:30 PM</option>
                                                    <option value="17:00">05:00 PM</option>
                                                </select>
                                                <button type="submit" class="btn-action-reschedule" style="width: 100%;">Confirm Reschedule</button>
                                            </form>
                                        </details>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>