<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Grades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">1st Semester S.Y. 2024 - 2025 Grade Upload</h3>
                        
                        <!-- Instructions Card -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <h4 class="font-medium text-blue-700 mb-2">Important Instructions</h4>
                            <ul class="list-disc list-inside text-sm text-blue-600 space-y-1">
                                <li>Upload grades in CSV format only</li>
                                <li>First row must be the header row</li>
                                <li>Student ID must be included in the first column</li>
                                <li>All numeric values (Units, Midterm, Finals) must be numbers</li>
                                <li>Grades should be in the range of 1.0 to 5.0</li>
                            </ul>
                        </div>

                        <!-- CSV Format Example -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <p class="text-sm font-medium mb-2">Required CSV Format:</p>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2 text-left">Student ID</th>
                                            <th class="px-4 py-2 text-left">Subject</th>
                                            <th class="px-4 py-2 text-left">Description</th>
                                            <th class="px-4 py-2 text-left">Type</th>
                                            <th class="px-4 py-2 text-left">Units</th>
                                            <th class="px-4 py-2 text-left">Midterm</th>
                                            <th class="px-4 py-2 text-left">Finals</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-4 py-2">2020-00001</td>
                                            <td class="px-4 py-2">CC-HCI31</td>
                                            <td class="px-4 py-2">HUMAN COMPUTER INTERACTION</td>
                                            <td class="px-4 py-2">LEC</td>
                                            <td class="px-4 py-2">3</td>
                                            <td class="px-4 py-2">1.7</td>
                                            <td class="px-4 py-2">1.6</td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="px-4 py-2">2020-00001</td>
                                            <td class="px-4 py-2">CC-RESCOM31</td>
                                            <td class="px-4 py-2">METHODS OF RESEARCH IN COMPUTING</td>
                                            <td class="px-4 py-2">LEC</td>
                                            <td class="px-4 py-2">3</td>
                                            <td class="px-4 py-2">1.9</td>
                                            <td class="px-4 py-2">1.8</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.grades.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <!-- Student Selection -->
                        <div class="mb-4">
                            <livewire:admin.grades.upload />
                            <input type="hidden" id="student_id" name="student_id" required />
                            <script>
                                document.addEventListener('livewire:initialized', () => {
                                    Livewire.on('studentSelected', (studentId) => {
                                        document.getElementById('student_id').value = studentId;
                                    });
                                });
                            </script>
                                    </div>
                                </template>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <x-input-label for="grades_file" value="Select CSV File" />
                            <input type="file" id="grades_file" name="grades_file" accept=".csv"
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100"
                                required
                            />
                            <x-input-error :messages="$errors->get('grades_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Upload and Process Grades') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('student_search');
            const searchResults = document.getElementById('search-results');
            const hiddenInput = document.getElementById('student_id');
            const template = document.getElementById('scholar-template');
            const scholars = template.content.querySelectorAll('.scholar-item');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const results = Array.from(scholars).filter(scholar => {
                    const searchData = scholar.getAttribute('data-search');
                    return searchData && searchData.includes(searchTerm);
                });

                searchResults.innerHTML = '';
                if (searchTerm.length > 0) {
                    results.forEach(scholar => {
                        const clone = scholar.cloneNode(true);
                        clone.addEventListener('click', function() {
                            hiddenInput.value = this.getAttribute('data-id');
                            searchInput.value = this.querySelector('.font-medium').textContent;
                            searchResults.classList.add('hidden');
                        });
                        searchResults.appendChild(clone);
                    });
                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.classList.add('hidden');
                }
            });

            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
