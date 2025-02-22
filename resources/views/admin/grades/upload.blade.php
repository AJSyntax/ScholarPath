<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Grades') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.grades.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Upload Grades File</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Please upload a CSV file with the following columns:<br>
                            School Year, Semester, ID Number, Subject, Description, Type, Units, Midterm, Finals<br><br>
                            <span class="font-medium">Important:</span> Save your Excel file as CSV (Comma delimited) before uploading.
                        </p>
                        
                        <div class="mt-4">
                            <input type="file" 
                                name="grades_file" 
                                accept=".csv,.txt"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                required
                            />
                            @error('grades_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h4 class="font-medium text-gray-900 mb-2">Important Notes:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>Make sure your CSV file follows the exact column structure</li>
                            <li>The system will calculate GPA based on Finals grades</li>
                            <li>The least grade will be determined from Finals grades</li>
                            <li>Student ID Numbers must match existing records</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        >
                            Process Grades
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
