<div class="space-y-6">
    <div class="relative">
        <x-input-label for="student_search" value="Search Scholar" />
        <input type="text" id="student_search" wire:model.live="search"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            placeholder="Search by student number or name"
            autocomplete="off"
        />
        @if(!empty($suggestions))
            <div class="absolute z-10 w-full bg-white mt-1 rounded-md shadow-lg border border-gray-200">
                @foreach($suggestions as $student)
                    <div class="p-3 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                         wire:click="selectStudent({{ $student['id'] }})">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <div class="font-medium text-gray-900">{{ $student['student_number'] }}</div>
                                <div class="text-sm text-gray-600">{{ $student['name'] }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">{{ $student['course'] }}-{{ $student['year_level'] }}</div>
                                <div class="text-sm">
                                    <span class="font-medium {{ $student['scholarship_type'] === 'PRESIDENTIAL' ? 'text-blue-600' : 
                                        ($student['scholarship_type'] === 'ACADEMIC' ? 'text-green-600' : 
                                        ($student['scholarship_type'] === 'TDP' ? 'text-purple-600' : 
                                        ($student['scholarship_type'] === 'TES' ? 'text-orange-600' : 'text-gray-600'))) }}">
                                        {{ $student['scholarship_type'] }}
                                    </span>
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $student['status'] === 'NEW' ? 'bg-green-100 text-green-800' : 
                                        ($student['status'] === 'MAINTAINED' ? 'bg-blue-100 text-blue-800' : 
                                        ($student['status'] === 'TERMINATED' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $student['status'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($selectedStudentId)
        <div class="mt-6">
            <form wire:submit="uploadGrades">
                <div class="space-y-4">
                    <div>
                        <x-input-label for="grades_file" value="Upload Grades CSV" />
                        <input type="file" id="grades_file" wire:model="gradesFile" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                            accept=".csv,.txt" />
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 font-medium">File Requirements:</p>
                            <ul class="mt-1 text-sm text-gray-500 list-disc list-inside">
                                <li>File format: Tab-separated values (.txt or .csv)</li>
                                <li>Required columns: Subject, Units, Midterm, Finals</li>
                                <li>Optional columns: Description, Type</li>
                                <li>Units, Midterm, and Finals must be numeric values</li>
                                <li>First row must contain column headers</li>
                            </ul>
                            <p class="mt-2 text-sm text-gray-500">Example header rows (any of these will work):</p>
                            <code class="mt-1 block text-sm text-gray-600 bg-gray-50 p-2 rounded font-mono whitespace-pre">Subject	Description	Type	Units	Midterm	Finals
Subject	Description	Type	Units	Midterm	Finals</code>
                        </div>
                        @error('gradesFile') 
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <x-primary-button type="submit" wire:loading.attr="disabled" class="w-full justify-center">
                            <span wire:loading.remove wire:target="uploadGrades">Upload and Analyze Grades</span>
                            <span wire:loading wire:target="uploadGrades">Processing...</span>
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        @if($message)
            <div class="mt-4 p-4 rounded-md {{ $messageType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
                {{ $message }}
            </div>
        @endif
    @endif
</div>