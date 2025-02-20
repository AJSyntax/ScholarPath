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
                    <form method="POST" action="{{ route('student.scholarships.store') }}" class="space-y-6">
                        @csrf

                        <!-- Scholarship Selection -->
                        <div>
                            <x-input-label for="scholarship_id" value="Select Scholarship" />
                            <select id="scholarship_id" name="scholarship_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select a scholarship</option>
                                @foreach($scholarships as $scholarship)
                                    <option value="{{ $scholarship->id }}">{{ $scholarship->name }} ({{ $scholarship->discount_percentage }}% discount)</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('scholarship_id')" class="mt-2" />
                        </div>

                        <!-- Academic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="gpa" value="GPA" />
                                <x-text-input id="gpa" name="gpa" type="number" step="0.01" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('gpa')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="lowest_grade" value="Lowest Grade" />
                                <x-text-input id="lowest_grade" name="lowest_grade" type="number" step="0.01" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('lowest_grade')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Course Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="course" value="Course" />
                                <x-text-input id="course" name="course" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('course')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="year_level" value="Year Level" />
                                <select id="year_level" name="year_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Academic Period -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="academic_year" value="Academic Year" />
                                <x-text-input id="academic_year" name="academic_year" type="text" class="mt-1 block w-full" placeholder="2024-2025" required />
                                <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="semester" value="Semester" />
                                <select id="semester" name="semester" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1st">1st Semester</option>
                                    <option value="2nd">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('student.scholarships.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
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
