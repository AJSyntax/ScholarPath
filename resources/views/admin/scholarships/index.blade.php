<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Scholarships') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.scholarships.create') }}" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Add New Scholarship
                </a>
                <a href="{{ route('admin.scholarships.applications.index') }}" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View Applications
                </a>
                <a href="{{ route('admin.reports.index') }}" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Generate Reports
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($scholarships as $scholarship)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-semibold text-lg">{{ $scholarship->name }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $scholarship->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($scholarship->status) }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-4">{{ $scholarship->description }}</p>
                                
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Type:</span> {{ ucfirst($scholarship->type) }}</p>
                                    <p><span class="font-medium">Discount:</span> {{ $scholarship->discount_percentage }}%</p>
                                    
                                    <div>
                                        <p class="font-medium mb-1">Requirements:</p>
                                        <ul class="list-disc list-inside pl-2 text-gray-600">
                                            @foreach(json_decode($scholarship->requirements) as $requirement)
                                                <li>{{ $requirement }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-2 mt-4">
                                        <a href="{{ route('admin.scholarships.edit', $scholarship) }}" 
                                            class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.scholarships.destroy', $scholarship) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded"
                                                onclick="return confirm('Are you sure you want to delete this scholarship?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
