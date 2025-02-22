<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Preview Scholars List') }} - {{ ucfirst($type) }} Scholars
            </h2>
            <form action="{{ route('admin.reports.scholarships.export') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Excel
                </button>
            </form>
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
                        <p class="mt-4">OFFICIAL LIST OF {{ strtoupper($type) }} SCHOLARS</p>
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
                            <tbody>
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
                                            @switch($type)
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
