<x-app-layout>
    <x-slot name="header">
        Appointment Request Form
    </x-slot>

    <style>
        .form-card { 
            background: #ffffff; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
            padding: 28px; 
            margin-bottom: 24px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-label { 
            display: block; 
            font-size: 11px; 
            font-weight: 800; 
            color: #334155; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            margin-bottom: 8px; 
        }
        .form-input { 
            width: 100%; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            padding: 10px 14px; 
            font-size: 13px; 
            color: #0f172a; 
            background-color: #ffffff;
            box-sizing: border-box;
            transition: border-color 0.15s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn-submit {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .btn-submit:hover {
            background-color: #1d4ed8;
        }
        .records-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 12px; 
            text-align: left; 
        }
        .records-table th { 
            background-color: #f8fafc; 
            border-bottom: 2px solid #e2e8f0; 
            color: #475569; 
            font-size: 11px; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            padding: 12px 16px; 
        }
        .records-table td { 
            border-bottom: 1px solid #f1f5f9; 
            padding: 14px 16px; 
            font-size: 13px; 
            color: #1e293b; 
        }
        .status-pill { 
            padding: 4px 10px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase; 
            display: inline-block; 
        }
        .status-accepted { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .status-canceled { background-color: #ffe4e6; color: #be123c; border: 1px solid #fecdd3; }
        .status-rescheduled { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-pending { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    </style>

    <div style="max-width: 960px; margin: 0 auto;">
        @if (session('success'))
            <div style="padding: 12px 16px; background-color: #dcfce7; border: 1px solid #86efac; color: #166534; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- BOOK APPOINTMENT CARD -->
        <div class="form-card">
            <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 22px;">
                <h2 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">Schedule New Appointment</h2>
                <p style="font-size: 12px; color: #64748b; margin-top: 4px;">Please choose a doctor and pick an available 30-minute operational time slot.</p>
            </div>

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <!-- Doctor Select -->
                <div class="form-group">
                    <label for="doctor_name" class="form-label">Attending Physician</label>
                    <select id="doctor_name" name="doctor_name" class="form-input" required>
                        <option value="" disabled {{ old('doctor_name') ? '' : 'selected' }}>-- Choose a Doctor --</option>
                        <option value="Dr. Jose Rizal" {{ old('doctor_name') == 'Dr. Jose Rizal' ? 'selected' : '' }}>Dr. Jose Rizal</option>
                        <option value="Dr. Emilio Aguinaldo" {{ old('doctor_name') == 'Dr. Emilio Aguinaldo' ? 'selected' : '' }}>Dr. Emilio Aguinaldo</option>
                        <option value="Dr. Kyve Calimpusan" {{ old('doctor_name') == 'Dr. Kyve Calimpusan' ? 'selected' : '' }}>Dr. Kyve Calimpusan</option>
                    </select>
                    <x-input-error :messages="$errors->get('doctor_name')" class="mt-1" />
                </div>

                <!-- Date & 30-min Slot Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;" class="form-group">
                    <div>
                        <label for="appointment_date_only" class="form-label">Preferred Date</label>
                        <input id="appointment_date_only" type="date" name="appointment_date_only" min="{{ date('Y-m-d') }}" value="{{ old('appointment_date_only') }}" class="form-input" required />
                        <x-input-error :messages="$errors->get('appointment_date_only')" class="mt-1" />
                    </div>

                    <div>
                        <label for="appointment_time_slot" class="form-label">Time Slot (08:00 AM – 05:00 PM)</label>
                        <select id="appointment_time_slot" name="appointment_time_slot" class="form-input" required>
                            <option value="" disabled {{ old('appointment_time_slot') ? '' : 'selected' }}>-- Select Time Slot --</option>
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
                        <x-input-error :messages="$errors->get('appointment_time_slot')" class="mt-1" />
                    </div>
                </div>

                <!-- Medical Notes -->
                <div class="form-group">
                    <label for="medical_notes" class="form-label">Medical Notes / Symptoms (AES-256 Encrypted)</label>
                    <textarea id="medical_notes" name="medical_notes" rows="3" class="form-input" placeholder="Describe reason for medical visit..." required>{{ old('medical_notes') }}</textarea>
                    <x-input-error :messages="$errors->get('medical_notes')" class="mt-1" />
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-submit">
                        Submit Appointment Request
                    </button>
                </div>
            </form>
        </div>

        <!-- USER SCHEDULED APPOINTMENTS TABLE -->
        <div class="form-card">
            <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 16px;">
                <h2 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">My Appointment Records</h2>
            </div>

            @if($appointments->isEmpty())
                <p style="text-align: center; color: #94a3b8; padding: 24px 0; font-size: 13px;">No appointment records registered yet.</p>
            @else
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Physician</th>
                            <th>Date & Time</th>
                            <th>Medical Notes</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $app)
                            <tr>
                                <td style="font-weight: 700; color: #0f172a;">{{ $app->doctor_name }}</td>
                                <td style="font-weight: 600; color: #2563eb;">{{ \Carbon\Carbon::parse($app->appointment_date)->format('M d, Y - h:i A') }}</td>
                                <td style="color: #475569;">{{ $app->medical_notes }}</td>
                                <td>
                                    @if($app->status === 'Accepted')
                                        <span class="status-pill status-accepted">Accepted</span>
                                    @elseif($app->status === 'Canceled')
                                        <span class="status-pill status-canceled">Canceled</span>
                                        @if($app->cancellation_reason)
                                            <div style="font-size: 10px; color: #e11d48; margin-top: 4px;">Reason: {{ $app->cancellation_reason }}</div>
                                        @endif
                                    @elseif($app->status === 'Rescheduled')
                                        <span class="status-pill status-rescheduled">Rescheduled</span>
                                    @else
                                        <span class="status-pill status-pending">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>