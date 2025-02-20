<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('student.profile.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Profile
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Personal Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Student Number</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->student_number ?? 'Not set' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Course</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->course ?? 'Not set' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Year Level</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->year_level ?? 'Not set' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Current GPA</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->current_gpa ?? 'Not set' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2">Contact Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Contact Number</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->contact_number ?? 'Not set' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->address ?? 'Not set' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Parent Name</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->parent_name ?? 'Not set' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-500">Parent Contact</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">{{ $profile->parent_contact ?? 'Not set' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
