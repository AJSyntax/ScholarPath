<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Scholarship Reports') }}
            </h2>
            <div class="flex space-x-4">
                <select id="scholarshipType" onchange="updatePreview(this.value)" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach($scholarTypes as $scholarType)
                        <option value="{{ $scholarType }}" {{ request('type') == $scholarType ? 'selected' : '' }}>
                            {{ ucfirst($scholarType) }} Scholars
                        </option>
                    @endforeach
                </select>
                <form action="{{ route('admin.reports.scholarships.export') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="type" id="exportType" value="{{ request('type', $scholarTypes[0]) }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export to Excel
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 text-center">
                        <h1 class="text-xl font-bold">UNIVERSITY OF CEBU LAPU-LAPU & MANDAUE</h1>
                        <p>A.C CORTES AVE., LOOC MANDAUE CITY, CEBU</p>
                        <p class="font-semibold">SCHOLARSHIP OFFICE</p>
                        <p class="mt-4">OFFICIAL LIST OF <span id="scholarTypeTitle">{{ strtoupper(request('type', $scholarTypes[0])) }}</span> SCHOLARS</p>
                        <p>{{ strtoupper($semester) }} SEMESTER, A.Y. {{ $academicYear }}</p>
                        <p>(Approved Policy S.Y. {{ $academicYear }})</p>
                    </div>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full bg-white border">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">NO.</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">ID #</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">NAME OF SCHOLARS</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">COURSE & YEAR</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">GPA</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">LEAST GRADE</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">STATUS</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">PRIVILEGES</th>
                                    <th class="px-4 py-2 border bg-gray-50 text-xs font-medium text-gray-500 uppercase">REMARKS</th>
                                </tr>
                            </thead>
                            <tbody id="scholarsList">
                                @foreach($scholars as $index => $scholar)
                                    <tr>
                                        <td class="px-4 py-2 border text-sm">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 border text-sm">{{ $scholar->user->student_id }}</td>
                                        <td class="px-4 py-2 border text-sm font-medium">{{ strtoupper($scholar->user->name) }}</td>
                                        <td class="px-4 py-2 border text-sm">{{ $scholar->course }}-{{ $scholar->year_level }}</td>
                                        <td class="px-4 py-2 border text-sm">{{ number_format($scholar->current_gpa, 2) }}</td>
                                        <td class="px-4 py-2 border text-sm">{{ $scholar->lowest_grade }}</td>
                                        <td class="px-4 py-2 border text-sm">SUMMA CUM LAUDE</td>
                                        <td class="px-4 py-2 border text-sm">
                                            @switch(request('type', $scholarTypes[0]))
                                                @case('academic')
                                                    Full Free Grant (Free Tuition, Miscellaneous and Laboratory Fees including LMS)
                                                    @break
                                                @case('presidential')
                                                    Php 1,500 worth of Books per semester
                                                    @break
                                                @default
                                                    Standard scholarship benefits
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-2 border text-sm">
                                            {{ $scholar->created_at->format('Y') == date('Y') ? 'NEW' : '' }}
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updatePreview(type) {
            // Update hidden input for export
            document.getElementById('exportType').value = type;
            
            // Update the title
            document.getElementById('scholarTypeTitle').textContent = type.toUpperCase();
            
            // Show loading state
            document.getElementById('scholarsList').innerHTML = `
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3 text-blue-500" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading scholars...
                        </div>
                    </td>
                </tr>
            `;
            
            // Fetch and update the table content
            fetch(`{{ route('admin.reports.scholarships') }}?type=${type}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.scholars.forEach((scholar, index) => {
                    let privileges = '';
                    switch(type) {
                        case 'academic':
                            privileges = 'Full Free Grant (Free Tuition, Miscellaneous and Laboratory Fees including LMS)';
                            break;
                        case 'presidential':
                            privileges = 'Php 1,500 worth of Books per semester';
                            break;
                        default:
                            privileges = 'Standard scholarship benefits';
                    }

                    html += `
                        <tr>
                            <td class="px-4 py-2 border text-sm">${index + 1}</td>
                            <td class="px-4 py-2 border text-sm">${scholar.user.student_id}</td>
                            <td class="px-4 py-2 border text-sm font-medium">${scholar.user.name.toUpperCase()}</td>
                            <td class="px-4 py-2 border text-sm">${scholar.course}-${scholar.year_level}</td>
                            <td class="px-4 py-2 border text-sm">${Number(scholar.current_gpa).toFixed(2)}</td>
                            <td class="px-4 py-2 border text-sm">${scholar.lowest_grade}</td>
                            <td class="px-4 py-2 border text-sm">SUMMA CUM LAUDE</td>
                            <td class="px-4 py-2 border text-sm">${privileges}</td>
                            <td class="px-4 py-2 border text-sm">${new Date(scholar.created_at).getFullYear() === new Date().getFullYear() ? 'NEW' : ''}</td>
                        </tr>
                    `;
                });

                if (data.scholars.length === 0) {
                    html = `
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-center text-gray-500">
                                No scholars found.
                            </td>
                        </tr>
                    `;
                }

                document.getElementById('scholarsList').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('scholarsList').innerHTML = `
                    <tr>
                        <td colspan="9" class="px-4 py-4 text-center text-red-500">
                            Error loading scholars. Please try again.
                        </td>
                    </tr>
                `;
            });
        }
    </script>
</x-app-layout>
