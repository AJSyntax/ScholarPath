<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Grades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">1st Semester S.Y. 2024 - 2025 Grade Upload</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Please ensure your CSV file follows this format:<br>
                            Subject, Description, Type, Units, Midterm, Finals
                        </p>

                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <p class="text-sm font-medium mb-2">Example Format:</p>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2 text-left">Subject</th>
                                            <th class="px-4 py-2 text-left">Description</th>
                                            <th class="px-4 py-2 text-left">Type</th>
                                            <th class="px-4 py-2 text-left">Units</th>
                                            <th class="px-4 py-2 text-left">Midterm</th>
                                            <th class="px-4 py-2 text-left">Finals</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-4 py-2">CC-HCI31</td>
                                            <td class="px-4 py-2">HUMAN COMPUTER INTERACTION</td>
                                            <td class="px-4 py-2">LEC</td>
                                            <td class="px-4 py-2">3</td>
                                            <td class="px-4 py-2">1.7</td>
                                            <td class="px-4 py-2">1.6</td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="px-4 py-2">CC-RESCOM31</td>
                                            <td class="px-4 py-2">METHODS OF RESEARCH IN COMPUTING</td>
                                            <td class="px-4 py-2">LEC</td>
                                            <td class="px-4 py-2">3</td>
                                            <td class="px-4 py-2">1.9</td>
                                            <td class="px-4 py-2">1.8</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.grades.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="grades_file" value="Select CSV File" />
                            <input type="file" id="grades_file" name="grades_file" accept=".csv"
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100"
                                required
                            />
                        </div>

                        @error('grades_file')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Upload Grades') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
