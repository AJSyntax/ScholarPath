<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Scholarship') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('student.scholarships.apply', $scholarship) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <!-- Scholarship Information -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $scholarship->name }}</h3>
                            <p class="text-gray-600">{{ $scholarship->description }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $scholarship->type }} Scholarship
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 ml-2">
                                    {{ $scholarship->discount_percentage }}% Discount
                                </span>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="course" :value="__('Course')" />
                                    <x-text-input id="course" name="course" type="text" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('course')" />
                                </div>

                                <div>
                                    <x-input-label for="year_level" :value="__('Year Level')" />
                                    <select id="year_level" name="year_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Select Year Level</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('year_level')" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="gpa" :value="__('GPA')" />
                                    <x-text-input id="gpa" name="gpa" type="number" step="0.01" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('gpa')" />
                                </div>

                                <div>
                                    <x-input-label for="lowest_grade" :value="__('Lowest Grade')" />
                                    <x-text-input id="lowest_grade" name="lowest_grade" type="number" step="0.01" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('lowest_grade')" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="academic_year" :value="__('Academic Year')" />
                                    <x-text-input id="academic_year" name="academic_year" type="text" class="mt-1 block w-full" placeholder="2024-2025" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('academic_year')" />
                                </div>

                                <div>
                                    <x-input-label for="semester" :value="__('Semester')" />
                                    <select id="semester" name="semester" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
                                        <option value="3">Summer</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('semester')" />
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Required Documents</h3>
                            <div class="space-y-4">
                                @foreach(json_decode($scholarship->requirements) as $requirement)
                                <div>
                                    <x-input-label :for="'document_'.$loop->index" :value="$requirement" />
                                    <input type="file" :id="'document_'.$loop->index" :name="'documents[]'" class="mt-1 block w-full" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('documents.'.$loop->index)" />
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Submit Application') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
