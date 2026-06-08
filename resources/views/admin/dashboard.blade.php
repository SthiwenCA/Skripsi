<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen"
         x-data="{ currentTab: localStorage.getItem('adminTab') || 'verifikasi', showNotif: true }" 
         x-init="$watch('currentTab', val => localStorage.setItem('adminTab', val))">
         
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#4a3219] font-extrabold hover:text-[#382613] transition text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Map
                </a>
                <span class="text-gray-500 font-medium text-sm">Halaman Manajemen Administrator</span>
            </div>

            @if(session('success'))
                <div x-show="showNotif" x-transition class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="showNotif = false" class="text-green-700 hover:text-green-900 text-xl leading-none font-bold">&times;</button>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                
                <div class="flex flex-wrap border-b border-gray-200 bg-white">
                    <button @click="currentTab = 'verifikasi'; showNotif = false" 
                            :class="currentTab === 'verifikasi' ? 'border-b-2 border-[#4a3219] text-[#4a3219] font-extrabold' : 'text-gray-500 font-semibold hover:text-gray-700'" 
                            class="py-4 px-6 text-sm transition focus:outline-none flex items-center gap-2">
                        📋 Verifikasi Baru
                    </button>
                    <button @click="currentTab = 'validasi_fixed'; showNotif = false" 
                            :class="currentTab === 'validasi_fixed' ? 'border-b-2 border-[#4a3219] text-[#4a3219] font-extrabold' : 'text-gray-500 font-semibold hover:text-gray-700'" 
                            class="py-4 px-6 text-sm transition focus:outline-none flex items-center gap-2">
                        🛠️ Validasi Perbaikan
                    </button>
                    <button @click="currentTab = 'history'; showNotif = false" 
                            :class="currentTab === 'history' ? 'border-b-2 border-[#4a3219] text-[#4a3219] font-extrabold' : 'text-gray-500 font-semibold hover:text-gray-700'" 
                            class="py-4 px-6 text-sm transition focus:outline-none flex items-center gap-2">
                        📜 History
                    </button>
                    <button @click="currentTab = 'delete_data'; showNotif = false" 
                            :class="currentTab === 'delete_data' ? 'border-b-2 border-red-600 text-red-600 font-extrabold' : 'text-gray-500 font-semibold hover:text-red-500'" 
                            class="py-4 px-6 text-sm transition focus:outline-none flex items-center gap-2 ml-auto">
                        🗑️ Delete Laporan
                    </button>
                </div>

                <div x-show="currentTab === 'verifikasi'" x-cloak class="bg-gray-400 p-6">
                    <div class="flex flex-col gap-4">
                        @forelse($pendingReports as $report)
                            <div x-data="{ selectedType: '{{ $report->damage_type }}' }" class="flex flex-col md:flex-row items-center justify-between bg-gray-200 p-4 rounded-xl shadow-sm border border-gray-300">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-6 w-full md:w-3/4">
                                    <img src="{{ asset('storage/' . $report->image_path) }}" class="w-40 h-28 object-cover rounded-lg shadow-sm border border-gray-300 shrink-0" alt="Kerusakan">
                                    <div class="flex flex-col justify-center gap-2 text-gray-800 text-[15px] mt-2 md:mt-0 h-full py-1 w-full">
                                        <div class="leading-snug"><span class="font-bold">Address :</span> {{ $report->address }}</div>
                                        <div><span class="font-bold">Detected Damage Type :</span> <span class="capitalize">{{ $report->ai_detected_type ?? $report->damage_type }}</span></div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-bold">Verifikasi Damage Type :</span> 
                                            <select x-model="selectedType" class="font-bold capitalize border-2 border-[#a38771] rounded-lg pl-3 pr-10 py-1 bg-white text-sm text-[#4a3219] outline-none">
                                                <option value="crack">Crack</option>
                                                <option value="pothole">Pothole</option>
                                            </select>
                                        </div>
                                        <div><span class="font-bold">Submitted Date :</span> {{ \Carbon\Carbon::parse($report->submission_date)->format('d M Y') }}</div>
                                        <div><span class="font-bold">Submitted By :</span> <span class="capitalize text-amber-800 font-semibold">{{ $report->user->name ?? 'Unknown User' }}</span></div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/4 flex flex-col justify-center gap-3 border-t md:border-t-0 md:border-l border-gray-400 pt-4 md:pt-0 md:pl-6 mt-4 md:mt-0 h-full">
                                    <form action="{{ route('admin.report.approve', $report->id) }}" method="POST" class="w-full">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="damage_type" :value="selectedType">
                                        <button type="submit" class="w-full bg-[#1e7b2e] hover:bg-green-700 text-white font-bold py-2 rounded-lg text-sm shadow transition">Approve Peta</button>
                                    </form>
                                    <form action="{{ route('admin.report.reject', $report->id) }}" method="POST" class="w-full" onsubmit="return confirm('Tolak laporan ini karena tidak valid?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full bg-[#d97706] hover:bg-yellow-600 text-white font-bold py-2 rounded-lg text-sm shadow transition">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-200 py-10 text-center rounded-xl font-bold text-gray-500 shadow-sm border border-gray-300">🎉 Tidak ada laporan kerusakan baru yang perlu diverifikasi.</div>
                        @endforelse
                    </div>
                </div>

                <div x-show="currentTab === 'validasi_fixed'" x-cloak class="bg-gray-400 p-6">
                    <div class="flex flex-col gap-4">
                        @forelse($pendingFixedReports as $report)
                            <div class="flex flex-col md:flex-row items-center justify-between bg-gray-200 p-4 rounded-xl shadow-sm border border-gray-300">
                                <div class="flex flex-col md:flex-row items-start gap-6 w-full md:w-3/4">
                                    <div class="flex flex-col gap-1 items-center shrink-0">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">Foto Awal</span>
                                        <img src="{{ asset('storage/' . $report->image_path) }}" class="w-32 h-20 object-cover rounded shadow-sm border border-gray-300">
                                    </div>
                                    <div class="flex flex-col gap-1 items-center shrink-0">
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase">Bukti Perbaikan</span>
                                        <img src="{{ asset('storage/' . $report->fixed_image_path) }}" class="w-32 h-20 object-cover rounded shadow-sm border border-emerald-400">
                                    </div>
                                    <div class="flex flex-col justify-center gap-2 text-gray-800 text-[15px] mt-2 md:mt-0 h-full py-1 w-full">
                                        <div class="leading-snug"><span class="font-bold">Address :</span> {{ $report->address }}</div>
                                        <div><span class="font-bold">Damage Type :</span> <span class="capitalize">{{ $report->damage_type }}</span></div>
                                        <div><span class="font-bold">Status :</span> <span class="bg-orange-200 text-orange-800 text-[11px] px-2 py-0.5 rounded font-extrabold uppercase">Menunggu Validasi</span></div>
                                        <div><span class="font-bold">Submitted By :</span> <span class="capitalize text-amber-800 font-semibold">{{ $report->user->name ?? 'Unknown User' }}</span></div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/4 flex flex-col justify-center gap-3 border-t md:border-t-0 md:border-l border-gray-400 pt-4 md:pt-0 md:pl-6 mt-4 md:mt-0 h-full">
                                    <form action="{{ route('admin.report.approve-fixed', $report->id) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah jalan ini sudah benar-benar diperbaiki? Data akan dihapus dari peta publik dan diarsipkan.');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full bg-[#047857] hover:bg-emerald-700 text-white font-bold py-2 rounded-lg text-sm shadow transition">Validasi Selesai</button>
                                    </form>
                                    <form action="{{ route('admin.report.destroy', $report->id) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin MENGHAPUS PERMANEN seluruh laporan ini karena foto perbaikan palsu?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2 rounded-lg text-sm shadow transition">Delete Laporan</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-200 py-10 text-center rounded-xl font-bold text-gray-500 shadow-sm border border-gray-300">Belum ada pengajuan validasi jalan yang sudah diperbaiki.</div>
                        @endforelse
                    </div>
                </div>

                <div x-show="currentTab === 'history'" x-cloak class="bg-gray-400 p-6">
                    <div class="flex flex-col gap-4">
                        @forelse($allReports as $report)
                            <div class="flex flex-col md:flex-row items-center justify-between bg-gray-200 p-4 rounded-xl shadow-sm border border-gray-300 opacity-95">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-4 w-full">
                                    <div class="flex gap-2 shrink-0">
                                        <img src="{{ asset('storage/' . $report->image_path) }}" class="w-24 h-16 object-cover rounded shadow-sm border border-gray-300" title="Foto Awal">
                                        @if($report->fixed_image_path)
                                            <img src="{{ asset('storage/' . $report->fixed_image_path) }}" class="w-24 h-16 object-cover rounded shadow-sm border border-emerald-400" title="Foto Selesai">
                                        @endif
                                    </div>
                                    <div class="flex flex-col justify-center gap-1 text-gray-800 text-[14px] mt-2 md:mt-0 h-full py-1 w-full">
                                        <div class="leading-snug"><span class="font-bold">Address :</span> {{ $report->address }}</div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-bold">Status Histori :</span> 
                                            @if($report->status === 'approved')
                                                <span class="text-[10px] font-extrabold bg-blue-200 text-blue-800 px-2 py-0.5 rounded border border-blue-400 uppercase">Telah Tayang (Aktif)</span>
                                            @elseif($report->status === 'fixed')
                                                <span class="text-[10px] font-extrabold bg-green-200 text-green-800 px-2 py-0.5 rounded border border-green-400 uppercase">Fixed (Selesai & Arsip)</span>
                                            @elseif($report->status === 'denied')
                                                <span class="text-[10px] font-extrabold bg-red-200 text-red-800 px-2 py-0.5 rounded border border-red-400 uppercase">Rejected (Ditolak)</span>
                                            @endif
                                        </div>
                                        <div><span class="font-bold">Damage Type :</span> <span class="capitalize">{{ $report->damage_type }}</span></div>
                                        <div><span class="font-bold">Submitted By :</span> <span class="capitalize text-amber-800 font-semibold">{{ $report->user->name ?? 'Unknown User' }}</span></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-200 py-10 text-center rounded-xl font-bold text-gray-500 shadow-sm border border-gray-300">Belum ada rekam jejak laporan di database.</div>
                        @endforelse
                    </div>
                </div>

                <div x-show="currentTab === 'delete_data'" x-cloak class="bg-gray-400 p-6">
                    <div class="flex flex-col gap-4">
                        @forelse($allReports as $report)
                            <div class="flex flex-col md:flex-row items-center justify-between bg-red-50 p-4 rounded-xl shadow-sm border border-red-200">
                                <div class="flex flex-col md:flex-row items-center md:items-start gap-4 w-full md:w-3/4">
                                    <div class="flex gap-2 shrink-0">
                                        <img src="{{ asset('storage/' . $report->image_path) }}" class="w-24 h-16 object-cover rounded shadow-sm border border-gray-300" title="Foto Awal">
                                    </div>
                                    <div class="flex flex-col justify-center gap-1 text-gray-800 text-[14px] mt-2 md:mt-0 h-full py-1 w-full">
                                        <div class="leading-snug"><span class="font-bold">Address :</span> {{ $report->address }}</div>
                                        <div><span class="font-bold">Status saat ini :</span> <span class="uppercase font-bold text-gray-600">{{ $report->status }}</span></div>
                                        <div><span class="font-bold">Damage Type :</span> <span class="capitalize">{{ $report->damage_type }}</span></div>
                                        <div><span class="font-bold">Submitted By :</span> <span class="capitalize text-amber-800 font-semibold">{{ $report->user->name ?? 'Unknown User' }}</span></div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/4 flex flex-col justify-center gap-3 border-t md:border-t-0 md:border-l border-red-200 pt-4 md:pt-0 md:pl-6 mt-4 md:mt-0 h-full">
                                    <form action="{{ route('admin.report.destroy', $report->id) }}" method="POST" class="w-full" onsubmit="return confirm('AWAS! Apakah Anda yakin ingin menghapus permanen data ini beserta seluruh fotonya dari server?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2 rounded-lg text-sm shadow transition">
                                            Delete Permanen
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-200 py-10 text-center rounded-xl font-bold text-gray-500 shadow-sm border border-gray-300">Tidak ada data yang bisa dihapus.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>