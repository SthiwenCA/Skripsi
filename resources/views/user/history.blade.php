<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ filter: 'all', showNotif: true }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#4a3219] font-extrabold hover:text-[#382613] transition text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Map
                </a>
                <h2 class="text-3xl font-extrabold text-gray-900">List Laporan</h2>
                <div class="w-24"></div> </div>

            @if(session('success'))
                <div x-show="showNotif" x-transition class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm font-semibold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="showNotif = false" class="text-green-700 font-bold text-xl">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div x-show="showNotif" x-transition class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm font-semibold flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="showNotif = false" class="text-red-700 font-bold text-xl">&times;</button>
                </div>
            @endif

            <div class="bg-[#b3b3b3] rounded-2xl p-6 shadow-inner">
                
                <div class="mb-6 relative" x-data="{ openFilter: false }">
                    <button @click="openFilter = !openFilter" @click.outside="openFilter = false" class="flex items-center gap-2 bg-[#a38771] hover:bg-[#8c7460] text-white px-5 py-2 rounded-xl font-bold shadow-md transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    <div x-cloak x-show="openFilter" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100 font-semibold text-gray-700">
                        <button @click="filter = 'all'; openFilter = false" class="w-full text-left px-4 py-2 hover:bg-gray-100" :class="filter === 'all' ? 'text-[#4a3219] bg-orange-50' : ''">All Status</button>
                        <button @click="filter = 'pending'; openFilter = false" class="w-full text-left px-4 py-2 hover:bg-gray-100" :class="filter === 'pending' ? 'text-[#4a3219] bg-orange-50' : ''">Pending</button>
                        <button @click="filter = 'approved'; openFilter = false" class="w-full text-left px-4 py-2 hover:bg-gray-100" :class="filter === 'approved' ? 'text-[#4a3219] bg-orange-50' : ''">Approved</button>
                        <button @click="filter = 'fixed'; openFilter = false" class="w-full text-left px-4 py-2 hover:bg-gray-100" :class="filter === 'fixed' ? 'text-[#4a3219] bg-orange-50' : ''">Fixed</button>
                        <button @click="filter = 'denied'; openFilter = false" class="w-full text-left px-4 py-2 hover:bg-gray-100" :class="filter === 'denied' ? 'text-[#4a3219] bg-orange-50' : ''">Rejected</button>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    @forelse($reports as $report)
                        <div x-show="filter === 'all' || filter === '{{ $report->status === 'pending_fixed' ? 'approved' : $report->status }}'" x-transition 
                             class="flex flex-col md:flex-row bg-[#e8e4e1] rounded-2xl overflow-hidden shadow-sm border border-[#d1c7bd]">
                            
                            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 w-full md:w-3/4 p-5">
                                <img src="{{ asset('storage/' . $report->image_path) }}" class="w-32 h-24 object-cover rounded-xl shadow border border-gray-300 shrink-0" alt="Kerusakan">
                                
                                <div class="flex flex-col justify-center gap-3 text-gray-800 text-[14px] w-full">
                                    <div class="flex gap-2">
                                        <span class="font-bold shrink-0 w-28">Address</span>
                                        <span>: {{ $report->address }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="font-bold shrink-0 w-28">Damage Type</span>
                                        <span class="capitalize">: {{ $report->damage_type }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="font-bold shrink-0 w-28">Submitted Date</span>
                                        <span>: {{ \Carbon\Carbon::parse($report->submission_date)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-1/4 flex flex-col justify-center items-start md:items-center p-5 border-t md:border-t-0 md:border-l border-gray-400 gap-3"
                                 x-data="{ showUploadForm: false }">
                                
                                <div class="text-[15px] font-extrabold flex gap-2">
                                    <span class="text-black">Status :</span>
                                    @if($report->status === 'pending')
                                        <span class="text-gray-500">Pending</span>
                                    @elseif($report->status === 'approved')
                                        <span class="text-green-600">Approved</span>
                                    @elseif($report->status === 'pending_fixed')
                                        <span class="text-orange-500 text-sm">Menunggu Validasi</span>
                                    @elseif($report->status === 'fixed')
                                        <span class="text-black">Fixed</span>
                                    @elseif($report->status === 'denied')
                                        <span class="text-red-600">Rejected</span>
                                    @endif
                                </div>

                                @if($report->status === 'approved')
                                    <button x-show="!showUploadForm" @click="showUploadForm = true" class="w-full bg-[#4a3219] hover:bg-[#382613] text-white font-bold py-2 rounded-xl text-sm shadow transition">
                                        Mark as Fixed
                                    </button>
                                    
                                    <form x-cloak x-show="showUploadForm" action="{{ route('submissions.road-fixed', $report->id) }}" method="POST" enctype="multipart/form-data" class="w-full bg-white p-3 rounded-xl border shadow-inner flex flex-col gap-2" x-transition>
                                        @csrf
                                        <label class="text-[10px] font-bold text-gray-600">Upload Foto Perbaikan:</label>
                                        <input type="file" name="fixed_image" accept="image/*" required class="block w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:font-bold file:bg-[#a38771] file:text-white hover:file:bg-[#8c7460] cursor-pointer">
                                        
                                        <div class="flex gap-2 mt-1">
                                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 rounded text-xs transition">Submit</button>
                                            <button type="button" @click="showUploadForm = false" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-1 rounded text-xs transition">Batal</button>
                                        </div>
                                    </form>
                                @endif
                                
                            </div>
                        </div>
                    @empty
                        <div class="bg-[#e8e4e1] py-10 text-center rounded-2xl font-bold text-gray-500 border border-[#d1c7bd]">
                            Anda belum pernah membuat laporan kerusakan jalan.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>