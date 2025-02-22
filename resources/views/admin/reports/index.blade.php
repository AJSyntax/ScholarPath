<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Reports Navigation Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Scholarships Report Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Scholarships Report</h3>
                            <p class="text-gray-600 mb-4">View and export the list of scholars based on scholarship type and semester.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.reports.scholarships') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                    View Report
                                </a>
                            </div>
                        </div>

                        <!-- Applications Report Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Applications Report</h3>
                            <p class="text-gray-600 mb-4">View and export scholarship applications status and statistics.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.reports.applications') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                    View Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Statistics -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="text-green-500 text-2xl font-bold">{{ $totalScholars ?? 0 }}</div>
                                <div class="text-gray-600">Active Scholars</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="text-blue-500 text-2xl font-bold">{{ $pendingApplications ?? 0 }}</div>
                                <div class="text-gray-600">Pending Applications</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="text-purple-500 text-2xl font-bold">{{ $totalScholarships ?? 0 }}</div>
                                <div class="text-gray-600">Total Scholarships</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
