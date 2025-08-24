<x-layouts.base>

    <style>
        .fi-in-entry-label {
            font-weight: bold;
        }

        #jumlah {
            background-image: url('{{ asset('storage/images/bg.jpg') }}');
        }
    </style>

    <x-layouts.hero size="">
        <form wire:submit="check" method="post" class="w-full lg:px-52 h-fit px-5">
            <div class="lg:flex lg:flex-row shadow-md">
                <input type="text" required class="p-3 w-full text-black bg-white placeholder:text-gray-400"
                    wire:model="tiket" name="tiket" value="{{ old('tiket') }}" placeholder="Inputkan Kode Surat">
                <button type="submit"
                    class="p-3 flex flex-row lg:w-fit w-full justify-center gap-1 bg-red-500 text-white hover:bg-red-400">
                    <x-tabler-search />
                    <span>CHECK</span>
                </button>
                <a href="{{ url('/daftar') }}"
                    class="p-3 flex flex-row justify-center gap-1 bg-blue-500 text-white hover:bg-blue-400">
                    <x-tabler-notes />
                    <span>LAPORKAN</span>
                </a>
            </div>

            @if (session('status'))
                <div class="text-white text-sm mt-5">
                    Pemberitahuan : {{ session('status') }}
                </div>
            @endif

        </form>
    </x-layouts.hero>



    @if ($laporan)
        <section id="content" class="flex flex-row justify-center">
            <div class="form-container w-4xl shadow-md -mt-44 z-10 bg-white p-5">
                {{ $this->laporanInfolist }}
            </div>
        </section>
    @endif

    <section id="progress" class="flex flex-row justify-center">
        <div class="progress-grp relative p-20">
            <div class="cont grid lg:grid-cols-5 gap-5 relative">

                <div class="btn lg:col-span-5 flex flex-row justify-center items-center mb-5">
                    <h5 class="text-2xl text-center font-bold text-gray-400 text-shadow-2xs">JUMLAH PER STATUS</h5>
                </div>

                @foreach ($laporans['stats'] as $item)
                    @php
                        $name = '';
                        $reformat = '';

                        switch ($item['name']) {
                            case 'AKTIF':
                                $reformat = 'Baru Daftar';
                                break;
                            case 'PROSES':
                                $reformat = 'Diproses';
                                break;

                            case 'VERIFIKASI':
                                $reformat = 'Diverifikasi';
                                break;

                            case 'TINDAK_LANJUT':
                                $reformat = 'Ditindak Lanjuti';
                                break;

                            case 'BATAL':
                                $reformat = 'Dibatalkan';
                                break;

                            case 'SELESAI':
                                $reformat = 'Diselesaikan';
                                break;
                        }

                        $name = $reformat;
                    @endphp

                    <div class="fitur lg:w-50 flex flex-col items-center">
                        <div class="desc mt-2">
                            <h5 class="text-center text-5xl font-bold mb-5">{{ $item['total'] }}</h5>
                            <p class="text-center text-lg capitalize">{{ $name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="jumlah" class="flex flex-row justify-center">
        <div class="w-full py-20 text-center  text-white">
            <h5 class="font-bold text-3xl">Jumlah Yang Terdaftar Pada Sistem</h5>
            <h1 class="font-bold text-5xl mt-5">{{ $laporans['total'] }} Laporan</h1>
            {{-- 
            <div class="btn lg:col-span-5 flex flex-row justify-center items-center mt-10">
                <a href="/publik" wire:navigate
                    class="border-2 rounded px-5 py-3 font-bold border-white text-white hover:bg-white hover:text-red-300">LIHAT
                    SEMUA LAPORAN</a>
            </div> --}}
        </div>
    </section>


    <section id="progress" class="flex flex-row justify-center">
        <div class="progress-grp relative p-20">
            <div class="cont grid lg:grid-cols-5 gap-5 relative">

                <div class="btn lg:col-span-5 flex flex-row justify-center items-center mb-5">
                    <h5 class="text-2xl text-center font-bold text-gray-400 text-shadow-2xs">JUMLAH PER KLASIFIKASI</h5>
                </div>

                @foreach ($laporans['klass'] as $item)
                    @php
                        $name = '';

                        if ($item['name'] != 'POSYANKUMHAMDES') {
                            $name = str_replace('_', ' ', strtolower($item['name']));
                        } else {
                            $name = $item['name'];
                        }
                    @endphp

                    <div class="fitur lg:w-50 flex flex-col items-center">
                        <div class="desc mt-2">
                            <h5 class="text-center text-5xl font-bold mb-5">{{ $item['total'] }}</h5>
                            <p class="text-center text-lg text-gray-400 capitalize">{{ $name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="progress" class="flex flex-row justify-center">
        <div class="progress-grp relative p-20">
            <div class="cont grid lg:grid-cols-5 grid-cols-1 gap-5 relative">
                <h5 class="font-bold lg:text-2xl text-center text-gray-400 mb-5 lg:col-span-5">PROSES PENGAJUAN LAPORAN
                </h5>
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
                        <p class="text-center">Dalam 3 hari kerja, laporan Anda akan diverifikasi dan diteruskan
                            kepada Pemerintah Desa</p>
                    </div>
                </div>
                <div class="fitur w-50 flex flex-col items-center">
                    <div class="logo flex flex-row justify-center items-center w-20 h-20 shadow-md rounded-full">
                        <x-tabler-trekking />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Proses Tindak Lanjut</h5>
                        <p class="text-center">Dalam 5 hari kerja, Pemerintah Desa akan menindaklanjuti dan
                            membalas laporan Anda</p>
                    </div>
                </div>
                <div class="fitur w-50 flex flex-col items-center">
                    <div class="logo flex flex-row justify-center items-center w-20 h-20 shadow-md rounded-full">
                        <x-tabler-bubble-text />
                    </div>
                    <div class="desc mt-2">
                        <h5 class="text-center font-bold">Beri Tanggapan</h5>
                        <p class="text-center">Anda dapat menanggapi kembali balasan yang diberikan oleh Pemerintah Desa
                            dalam waktu 10 hari</p>
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
