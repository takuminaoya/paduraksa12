<div x-data="{ navbarOpen: false, scrolledFromTop: false }" x-init="window.pageYOffset > 60 ? scrolledFromTop = true : scrolledFromTop = false"
    @scroll.window="window.pageYOffset > 60 ? scrolledFromTop = true : scrolledFromTop = false">
    <header
        class="fixed w-full py-6 h-24 text-lg flex justify-between transition-all duration-200 bg-red-700 items-center px-6 text-white z-50"
        :class="{ 'h-24': !scrolledFromTop, 'h-12': scrolledFromTop }">
        <a href="" class="font-bold flex flex-row justify-center items-center gap-1">
            <img src="{{ asset('storage/images/icon_trans.png') }}" class="h-11" alt="">
            Paduraksa
        </a>

        <nav>
            <button @click="navbarOpen = !navbarOpen" class="md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

            </button>

            <ul class="fixed w-full left-0 right-0 min-h-screen transition duration-200 bg-red-700 space-y-4 p-6 transform translate-x-full md:relative md:flex md:min-h-0 md:space-y-0 md:space-x-6 md:p-0 md:translate-x-0"
                :class="{ 'translate-x-full': !navbarOpen, 'translate-x-0': navbarOpen }">
                <li><a href="" class="hover:border-b-2 pb-1 font-s">Home</a></li>
                <li><a href="" class="hover:border-b-2 pb-1 font-s">Tentang</a></li>
                <li><a href="" class="hover:border-b-2 pb-1 font-s">Petunjuk Penggunaan</a></li>
            </ul>
        </nav>
    </header>

    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <section>
        <div id="hero" class="relative flex flex-row min-h-96 justify-center pt-40 pb-52 bg-red-700 text-white">
            <div class="container w-full text-center pt-20">
                <h2 class="b text-4xl font-bold">Pengaduan Aspirasi Masyarakat Desa Ungasan</h2>
                <p class="text-xl mt-3 mb-10">Sampaikan laporan Anda langsung kepada instansi pemerintah desa ungasan.
                </p>
            </div>

            <svg class="absolute bottom-0" width="100%" height="160px" viewBox="0 0 1300 160"
                preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink">
                <g>
                    <path
                        d="M1300,160 L-5.68434189e-14,160 L-5.68434189e-14,119 C423.103102,41.8480501 1096.33049,180.773108 1300,98 L1300,160 Z"
                        fill="#FFFFFF" fill-rule="nonzero"></path>
                    <path
                        d="M129.77395,40.2373685 C292.925845,31.2149964 314.345174,146.772453 615.144273,151.135393 C915.94337,155.498333 1017.27057,40.8373289 1240.93447,40.8373289 C1262.89392,40.8373289 1282.20864,41.9705564 1299.18628,44.0144896 L1300,160 L-1.0658141e-14,160 L-1.0658141e-14,105 C31.4276111,70.4780448 73.5616946,43.3459311 129.77395,40.2373685 Z"
                        fill="#FFFFFF" fill-rule="nonzero" opacity="0.3"></path>
                    <path
                        d="M69.77395,60.2373685 C232.925845,51.2149964 314.345174,146.772453 615.144273,151.135393 C915.94337,155.498333 1017.27057,0.837328936 1240.93447,0.837328936 C1263.91283,0.837328936 1283.59768,0.606916225 1300,1 L1300,160 L-1.70530257e-13,160 L-9.9475983e-14,74 C-9.9475983e-14,74 36.9912359,62.0502671 69.77395,60.2373685 Z"
                        fill="#FFFFFF" fill-rule="nonzero" opacity="0.3"></path>
                    <path
                        d="M2.27373675e-13,68 C23.2194389,95.7701288 69.7555676,123.009338 207,125 C507.7991,129.36294 698.336099,22 922,22 C1047.38026,22 1198.02057,63.2171658 1300,101 L1300,160 L0,160 L2.27373675e-13,68 Z"
                        fill="#FFFFFF" fill-rule="nonzero" opacity="0.3"
                        transform="translate(650, 91) scale(-1, 1) translate(-650, -91) "></path>
                </g>
            </svg>
        </div>

    </section>
    <section id="content" class="min-h-screen">
        <div class="form-container">
            @livewire('create-laporan')
        </div>
    </section>
</div>
