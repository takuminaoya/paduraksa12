<x-layouts.base>
    <x-layouts.hero />
    
    <section id="content" class="flex flex-row justify-center">
        <div class="form-container w-4xl shadow-md -mt-44 z-10 bg-white p-5">
            @livewire('create-laporan')
        </div>
    </section>

    <section id="progress" class="flex flex-row justify-center">
        <div class="progress-grp relative p-20">
            <div class="cont grid lg:grid-cols-5 gap-5 relative">
                <div class="fitur w-50 flex flex-col items-center">
                    <div
                        class="logo flex flex-row justify-center items-center w-20 h-20 bg-red-700 text-white rounded-full">
                        <x-tabler-edit />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Tautan Dilaporkan</h5>
                        <p class="text-center">Laporkan keluhan atau aspirasi anda dengan jelas dan lengkap</p>
                    </div>
                </div>
                <div class="fitur w-50 flex flex-col items-center">
                    <div class="logo flex flex-row justify-center items-center w-20 h-20 shadow-md rounded-full">
                        <x-tabler-checklist />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Proses Verifikasi</h5>
                        <p class="text-center">Dalam 3 hari, laporan Anda akan diverifikasi dan diteruskan kepada
                            instansi berwenang</p>
                    </div>
                </div>
                <div class="fitur w-50 flex flex-col items-center">
                    <div class="logo flex flex-row justify-center items-center w-20 h-20 shadow-md rounded-full">
                        <x-tabler-trekking />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Proses Tindak Lanjut</h5>
                        <p class="text-center">Dalam 5 hari, instansi akan menindaklanjuti dan membalas laporan Anda</p>
                    </div>
                </div>
                <div class="fitur w-50 flex flex-col items-center">
                    <div class="logo flex flex-row justify-center items-center w-20 h-20 shadow-md rounded-full">
                        <x-tabler-bubble-text />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Beri Tanggapan</h5>
                        <p class="text-center">Anda dapat menanggapi kembali balasan yang diberikan oleh instansi dalam
                            waktu 10 hari</p>
                    </div>
                </div>
                <div class="fitur w-50 flex flex-col items-center">
                    <div class="logo flex flex-row justify-center items-center w-20 h-20 shadow-md rounded-full">
                        <x-tabler-checks />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Selesai</h5>
                        <p class="text-center">Laporan Anda akan terus ditindaklanjuti hingga terselesaikan</p>
                    </div>
                </div>

                <div class="btn lg:col-span-5 flex flex-row justify-center items-center mt-5">
                    <a href="/tentang" wire:navigate
                        class="border-2 rounded px-5 py-3 font-bold border-red-700 text-red-700 hover:bg-red-700 hover:text-white">PELAJARI
                        LEBIH LANJUT</a>
                </div>
            </div>
        </div>
    </section>
    
</x-layouts.base>
