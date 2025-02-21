<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Reports') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        search: '',
        scholars: [],
        init() {
            this.scholars = [...document.querySelectorAll('tbody tr')].map(row => ({
                element: row,
                searchText: row.textContent.toLowerCase()
            }));
        },
        filterScholars() {
            const searchTerm = this.search.toLowerCase();
            this.scholars.forEach(scholar => {
                scholar.element.style.display = 
                    scholar.searchText.includes(searchTerm) ? '' : 'none';
            });
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <x-input-label for="search" :value="__('Search Scholars')" />
                        <x-text-input 
                            id="search" 
                            type="text" 
                            class="mt-1 block w-full" 
                            x-model="search"
                            x-on:input="filterScholars()"
                            placeholder="Search by name, ID number, course..."
                        />
                    </div>

                    <!-- Filters -->
                    <div class="mb-6">
                        <form action="{{ route('admin.reports.scholarships') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="course" :value="__('Course')" />
                                    <select id="course" name="course" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Courses</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course }}" {{ request('course') == $course ? 'selected' : '' }}>{{ $course }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="year_level" :value="__('School Year')" />
                                    <select id="year_level" name="year_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Years</option>
                                        @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'] as $year)
                                            <option value="{{ $year }}" {{ request('year_level') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="semester" :value="__('Semester')" />
                                    <select id="semester" name="semester" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Semesters</option>
                                        <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                        <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                        <option value="summer" {{ request('semester') == 'summer' ? 'selected' : '' }}>Summer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <x-primary-button type="submit">Filter</x-primary-button>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Least Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($scholars as $scholar)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $scholar->studentProfile->student_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $scholar->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $scholar->course }} - {{ $scholar->year_level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $scholar->studentProfile->least_grade }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $scholar->studentProfile->current_gpa }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @switch($scholar->scholarship->type)
                                                @case('presidential')
                                                    <span class="text-green-800 font-semibold">FULL FREE GRANT FOR PRESIDENTIAL SCHOLARS</span>
                                                    @break
                                                @case('academic')
                                                    <span class="text-blue-800 font-semibold">100% Discount on tuition fee</span>
                                                    @break
                                                @case('ched')
                                                    <span class="text-indigo-800 font-semibold">50% Discount</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @switch($scholar->status)
                                                @case('new')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">NEW</span>
                                                    @break
                                                @case('maintained')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">MAINTAINED</span>
                                                    @break
                                                @case('terminated')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">TERMINATED</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($scholars->isEmpty())
                            <div class="text-center py-4 text-gray-500">
                                No scholars found.
                            </div>
                        @endif

                        <div class="mt-4">
                            {{ $scholars->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
