<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Student Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">{{ $application->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $application->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Course</p>
                                <p class="font-medium">{{ $application->course }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Year Level</p>
                                <p class="font-medium">{{ $application->year_level }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">GPA</p>
                                <p class="font-medium">{{ $application->gpa }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lowest Grade</p>
                                <p class="font-medium">{{ $application->lowest_grade }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Academic Year</p>
                                <p class="font-medium">{{ $application->academic_year }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Scholarship Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Scholarship Information</h3>
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Application Documents -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Submitted Documents</h3>
                        <div class="space-y-2">
                            @foreach($application->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <span class="text-sm font-medium">{{ $document->name }}</span>
                                    <a href="{{ route('admin.applications.download-document', ['application' => $application->id, 'document' => $document->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Application Processing -->
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Process Application</h3>
                        <form method="POST" action="{{ route('admin.applications.update-status', $application) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <x-input-label for="status" value="Update Status" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approve</option>
                                    <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="remarks" value="Remarks" />
                                <textarea id="remarks" name="remarks" rows="3" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('remarks', $application->remarks) }}</textarea>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <x-secondary-button onclick="window.history.back()">Back</x-secondary-button>
                                <x-primary-button>Update Status</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>