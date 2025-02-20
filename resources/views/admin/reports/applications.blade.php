<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form action="{{ route('admin.reports.applications') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="scholarship" :value="__('Scholarship')" />
                                    <select id="scholarship" name="scholarship" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Scholarships</option>
                                        @foreach($scholarships as $scholarship)
                                            <option value="{{ $scholarship->id }}">{{ $scholarship->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="date_range" :value="__('Date Range')" />
                                    <input type="date" id="date_range" name="date_range" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <x-primary-button>
                                    {{ __('Generate Report') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Report Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholarship</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($applications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->scholarship->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->course }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->gpa }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($application->status === 'approved') bg-green-100 text-green-800 
                                            @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $application->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Export Buttons -->
                    <div class="mt-4 flex justify-end space-x-4">
                        <x-secondary-button onclick="window.location.href='{{ route('admin.reports.applications.export', ['format' => 'pdf']) }}'">
                            Export PDF
                        </x-secondary-button>
                        <x-secondary-button onclick="window.location.href='{{ route('admin.reports.applications.export', ['format' => 'excel']) }}'">
                            Export Excel
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
