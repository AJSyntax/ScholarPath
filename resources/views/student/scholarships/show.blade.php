<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Scholarship Information -->
                    <div class="mb-8">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $scholarship->name }}</h3>
                                <div class="mt-2 flex space-x-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $scholarship->type }} Scholarship
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $scholarship->discount_percentage }}% Discount
                                    </span>
                                </div>
                            </div>
                            <div>
                                @if(!$hasApplied)
                                    <a href="{{ route('student.scholarships.apply', $scholarship) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Apply Now
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Already Applied
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 prose max-w-none">
                            <p class="text-gray-600">{{ $scholarship->description }}</p>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Requirements</h4>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            @foreach(json_decode($scholarship->requirements) as $requirement)
                                <li>{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Eligibility Criteria -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Eligibility Criteria</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Minimum GPA Required</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $scholarship->min_gpa }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Maximum Lowest Grade Allowed</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $scholarship->max_lowest_grade }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Important Dates -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Application Start</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $scholarship->application_start->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Application Deadline</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $scholarship->application_end->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Results Announcement</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $scholarship->results_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
