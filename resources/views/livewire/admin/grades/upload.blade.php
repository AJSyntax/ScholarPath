<div class="relative">
    <x-input-label for="student_search" value="Search Scholar" />
    <input type="text" id="student_search" wire:model.live="search"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        placeholder="Search by name or student number"
        autocomplete="off"
    />
    @if(!empty($suggestions))
        <div class="absolute z-10 w-full bg-white mt-1 rounded-md shadow-lg border border-gray-200">
            @foreach($suggestions as $student)
                <div class="p-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                     wire:click="selectStudent({{ $student['id'] }})">
                    <div class="font-medium text-gray-900">{{ $student['student_number'] }}</div>
                    <div class="text-sm text-gray-600">{{ $student['name'] }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>