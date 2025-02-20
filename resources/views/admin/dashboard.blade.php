<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Statistics Cards -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4">Active Scholars</h3>
                            <p class="text-3xl font-bold">{{ $activeScholars }}</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4">Pending Applications</h3>
                            <p class="text-3xl font-bold">{{ $pendingApplications }}</p>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="mt-8 mb-6">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="search" value="Search Student" />
                                    <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" 
                                        placeholder="Name or Student Number"
                                        value="{{ request('search') }}" />
                                </div>
                                <div>
                                    <x-input-label for="scholarship_type" value="Scholarship Type" />
                                    <select id="scholarship_type" name="scholarship_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Types</option>
                                        <option value="academic" {{ request('scholarship_type') === 'academic' ? 'selected' : '' }}>Academic</option>
                                        <option value="athletic" {{ request('scholarship_type') === 'athletic' ? 'selected' : '' }}>Athletic</option>
                                        <option value="cultural" {{ request('scholarship_type') === 'cultural' ? 'selected' : '' }}>Cultural</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="status" value="Status" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <x-primary-button type="submit" class="w-full justify-center">
                                        Filter
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Recent Applications Table -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Recent Applications</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholarship</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($applications as $application)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $application->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ optional($application->user->studentProfile)->student_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->scholarship->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $application->course }} ({{ $application->year_level }})
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                       ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.scholarships.applications.show', $application->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                No applications found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
