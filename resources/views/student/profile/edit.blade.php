<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="student_number" value="Student Number" />
                                    <x-text-input id="student_number" name="student_number" type="text" class="mt-1 block w-full"
                                        :value="old('student_number', $profile->student_number)" required />
                                    <x-input-error :messages="$errors->get('student_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="course" value="Course" />
                                    <x-text-input id="course" name="course" type="text" class="mt-1 block w-full"
                                        :value="old('course', $profile->course)" required />
                                    <x-input-error :messages="$errors->get('course')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="year_level" value="Year Level" />
                                    <select id="year_level" name="year_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ (old('year_level', $profile->year_level) == $i) ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="current_gpa" value="Current GPA" />
                                    <x-text-input id="current_gpa" name="current_gpa" type="number" step="0.01" class="mt-1 block w-full"
                                        :value="old('current_gpa', $profile->current_gpa)" />
                                    <x-input-error :messages="$errors->get('current_gpa')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="contact_number" value="Contact Number" />
                                    <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full"
                                        :value="old('contact_number', $profile->contact_number)" required />
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" value="Address" />
                                    <textarea id="address" name="address" rows="3"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>{{ old('address', $profile->address) }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="birth_date" value="Birth Date" />
                                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full"
                                        :value="old('birth_date', $profile->birth_date?->format('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="parent_name" value="Parent Name" />
                                    <x-text-input id="parent_name" name="parent_name" type="text" class="mt-1 block w-full"
                                        :value="old('parent_name', $profile->parent_name)" required />
                                    <x-input-error :messages="$errors->get('parent_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="parent_contact" value="Parent Contact" />
                                    <x-text-input id="parent_contact" name="parent_contact" type="text" class="mt-1 block w-full"
                                        :value="old('parent_contact', $profile->parent_contact)" required />
                                    <x-input-error :messages="$errors->get('parent_contact')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('student.profile.show') }}'" type="button" class="mr-3">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Save Changes
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
