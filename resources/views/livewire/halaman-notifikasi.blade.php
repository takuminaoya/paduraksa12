<x-layouts.base>
    <section id="content" class="flex flex-row justify-center">
        <div class="form-container w-4xl shadow-md -mt-50 z-10 bg-white p-10">

            <h5 class="mb-10 text-center text-2xl font-bold">Selamat Laporan anda telah berhasil di laporkan.</h5>

            @if (session('status'))
                <div class="alert py-2 px-5 text-center text-white border rounded-md bg-red-700 mb-5 text-xs">
                    Notifikasi : {{ session('status') }}
                </div>
            @endisset

            <p class="mb-5">
                <strong>Pemberitahuan:</strong><br>

                Laporan Anda telah berhasil disimpan ke dalam sistem kami. Proses verifikasi akan dilakukan dalam
                waktu
                3–5 hari kerja. Apabila laporan ini memerlukan tindak lanjut atau telah selesai diverifikasi, kami
                akan
                mengirimkan pemberitahuan melalui pesan WhatsApp ke nomor yang Anda cantumkan saat pengajuan
                laporan.
            </p>

            <h5 class="font-bold">Informasi</h5>
            <p class="mb-5">Jika anda tidak mendapatkan pesan whatsapp otomatis dari kami. berarti ada kemunkinan
                nomor telpon yang anda inputkan salah. anda bisa menginputkan kembali nomor nda di kotak dibawah.
                untuk mengupdate ulang nomor whatsapp anda. </p>

            <div class="input mb-5">
                <form wire:submit="update" method="post">
                    @csrf
                    <input type="text" required class="w-full border p-2 placeholder:text-gray-400"
                        name="no_telpon" wire:model="no_telpon" placeholder="Contoh : 82359351665" id="">
                    <div class="text-xs">Nomor anda sebelumnya : <span
                            class="font-bold">{{ $laporan->no_telpon }}</span></div>

                    <button class="py-2 px-4 border mt-2 bg-red-700 hover:bg-red-400 text-white rounded-md"
                        type="submit">Update Nomor Whatsapp</button>
                </form>
            </div>

            <p>
                Terima kasih atas kepercayaan Anda dalam menggunakan layanan pelaporan ini. <br>
                Jika Anda membutuhkan bantuan lebih lanjut, silakan hubungi petugas.
            </p>
    </div>
</section>

<section id="progress" class="flex flex-row justify-center">
    <div class="progress-grp relative p-20">
        <div class="cont grid lg:grid-cols-5 gap-5 relative">
            <div class="fitur w-50 flex flex-col items-center">
                <div class="logo flex flex-row justify-center items-center w-20 h-20  shadow-md rounded-full">
                    <x-tabler-edit />
                </div>
                <div class="desc mt-2">
                    <h5 class="text-center font-bold">Tautan Dilaporkan</h5>
                    <p class="text-center">Laporkan keluhan atau aspirasi anda dengan jelas dan lengkap</p>
                </div>
            </div>
            <div class="fitur w-50 flex flex-col items-center">
                <div
                    class="logo flex flex-row justify-center items-center w-20 h-20 bg-red-700 text-white shadow-md rounded-full">
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
                <a href="#"
                    class="border-2 rounded px-5 py-3 font-bold border-red-700 text-red-700 hover:bg-red-700 hover:text-white">PELAJARI
                    LEBIH LANJUT</a>
            </div>
        </div>
    </div>
</section>
</x-layouts.base>
