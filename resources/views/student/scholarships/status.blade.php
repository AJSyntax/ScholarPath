<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Scholarship Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Scholarship Name</p>
                                <p class="font-medium">{{ $application->scholarship->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Type</p>
                                <p class="font-medium">{{ ucfirst($application->scholarship->type) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Discount</p>
                                <p class="font-medium">{{ $application->scholarship->discount_percentage }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Application Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Academic Year</p>
                                <p class="font-medium">{{ $application->academic_year }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Semester</p>
                                <p class="font-medium">{{ $application->semester }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Course</p>
                                <p class="font-medium">{{ $application->course }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Year Level</p>
                                <p class="font-medium">{{ $application->year_level }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">GPA</p>
                                <p class="font-medium">{{ $application->gpa }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lowest Grade</p>
                                <p class="font-medium">{{ $application->lowest_grade }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('student.scholarships.index') }}" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Scholarships
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
