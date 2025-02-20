<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Report Generation Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Generate Report</h3>
                    
                    <form action="{{ route('admin.reports.generate') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="academic_year" value="Academic Year" />
                                <x-text-input id="academic_year" name="academic_year" type="text" 
                                    class="mt-1 block w-full" required placeholder="2024-2025" />
                            </div>

                            <div>
                                <x-input-label for="semester" value="Semester" />
                                <select id="semester" name="semester" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1st">1st Semester</option>
                                    <option value="2nd">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="type" value="Scholarship Type" />
                                <select id="type" name="type" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="academic">Academic</option>
                                    <option value="presidential">Presidential</option>
                                    <option value="ched">CHED</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                Generate Report
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Preview -->
            @if(isset($applications))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Report Preview</h3>
                        <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Print Report
                        </button>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-medium">Filters:</h4>
                        <p class="text-sm text-gray-600">
                            Academic Year: {{ $validated['academic_year'] }} |
                            Semester: {{ $validated['semester'] }} |
                            Type: {{ ucfirst($validated['type']) }}
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholarship</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($applications as $application)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $application->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $application->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->course }}</div>
                                            <div class="text-sm text-gray-500">Year {{ $application->year_level }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->gpa }}</div>
                                            <div class="text-sm text-gray-500">Lowest: {{ $application->lowest_grade }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->scholarship->name }}</div>
                                            <div class="text-sm text-gray-500">{{ ucfirst($application->scholarship->type) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $application->scholarship->discount_percentage }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" colspan="5">
                                            No approved applications found for the selected criteria
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
