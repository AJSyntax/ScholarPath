<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Scholarship') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.scholarships.update', $scholarship) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" value="Scholarship Name" />
                            <x-text-input id="name" name="name" type="text" 
                                class="mt-1 block w-full" 
                                :value="old('name', $scholarship->name)" 
                                required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                required>{{ old('description', $scholarship->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" value="Type" />
                            <select id="type" name="type" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="academic" {{ $scholarship->type === 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="presidential" {{ $scholarship->type === 'presidential' ? 'selected' : '' }}>Presidential</option>
                                <option value="ched" {{ $scholarship->type === 'ched' ? 'selected' : '' }}>CHED</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="discount_percentage" value="Discount Percentage" />
                            <x-text-input id="discount_percentage" name="discount_percentage" type="number" 
                                min="0" max="100" 
                                class="mt-1 block w-full" 
                                :value="old('discount_percentage', $scholarship->discount_percentage)" 
                                required />
                            <x-input-error :messages="$errors->get('discount_percentage')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="requirements" value="Requirements (one per line)" />
                            <textarea id="requirements" name="requirements" rows="4" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                required>{{ old('requirements', implode("\n", json_decode($scholarship->requirements))) }}</textarea>
                            <x-input-error :messages="$errors->get('requirements')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="active" {{ $scholarship->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $scholarship->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Update Scholarship</x-primary-button>
                            <a href="{{ route('admin.scholarships.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
