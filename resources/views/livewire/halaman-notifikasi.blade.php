<x-layouts.base>
    <x-layouts.hero />

    <section id="content" class="flex flex-row justify-center">
        <div class="form-container w-4xl shadow-md -mt-50 z-10 bg-white p-10">

            <h5 class="text-center text-2xl font-bold">Selamat Laporan anda telah berhasil di laporkan.</h5>
            <h5 class="mb-10 text-center text-xl">Dengan Nomor Tiket : <span class="font-bold">{{ $laporan->tiket }}</span></h5>

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


</x-layouts.base>
