<x-app-layout>
    <x-slot name="header">
        My Prescriptions & Medical Records
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Prescribed Medications</h3>

            @if($prescriptions->isEmpty())
                <div class="p-4 bg-blue-50 text-blue-700 rounded-lg text-sm">
                    No prescriptions have been recorded for your account yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 text-sm font-semibold text-gray-700">Date Prescribed</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Doctor</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Medication</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Dosage</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescriptions as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-700">{{ $item->prescribed_date }}</td>
                                    <td class="p-3 text-sm text-gray-800 font-medium">{{ $item->doctor_name }}</td>
                                    <td class="p-3 text-sm text-indigo-600 font-semibold">{{ $item->medication_name }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $item->dosage }}</td>
                                    <td class="p-3 text-sm text-gray-600">{{ $item->instructions ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>