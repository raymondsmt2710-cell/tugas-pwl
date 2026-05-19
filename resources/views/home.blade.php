<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autopahala</title>

    @vite('resources/css/app.css')

</head>
<body class="bg-gray-50">

    <!-- NAVBAR -->

    <nav class="bg-white shadow-md">

        <div class="max-w-7xl mx-auto px-8 py-5 flex justify-between items-center">

            <h1 class="text-3xl font-bold text-green-600">
                Autopahala
            </h1>

            <div class="space-x-6 font-semibold">

                <a href="" class="hover:text-green-600">
                    Home
                </a>

                <a href="" class="hover:text-green-600">
                    Campaign
                </a>

                <a href="" class="hover:text-green-600">
                    Login
                </a>

            </div>

        </div>

    </nav>

    <!-- HERO -->

    <section class="relative h-screen">

    <img
        src="https://picsum.photos/1920/1080"
        class="w-full h-full object-cover"
    >

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="absolute inset-0 flex flex-col justify-center items-center text-white text-center px-5">

        <h1 class="text-6xl font-bold mb-6">
            Bantu Mereka Yang Membutuhkan
        </h1>

        <p class="text-xl mb-8 max-w-2xl">
            Platform donasi online untuk membantu sesama dan
            membuat kebaikan menjadi lebih mudah.
        </p>

        <button class="bg-green-500 hover:bg-green-600 px-8 py-4 rounded-xl text-lg font-semibold">
            Mulai Donasi
        </button>

    </div>

</section>

    <!-- SEARCH -->

    <section class="bg-white py-16">

        <div class="max-w-4xl mx-auto text-center">

            <h2 class="text-4xl font-bold mb-8">
                Cari Campaign
            </h2>

            <div class="flex gap-4 justify-center">

                <input
                    type="text"
                    placeholder="Cari campaign..."
                    class="border w-2/3 p-4 rounded-xl"
                >

                <button class="bg-green-500 text-white px-8 rounded-xl">
                    Cari
                </button>

            </div>

        </div>

    </section>

    <!-- CAMPAIGN -->

    <section class="py-20 px-10">

        <h2 class="text-4xl font-bold text-center mb-14">
            Campaign Populer
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <!-- CARD 1 -->

            <div class="bg-white rounded-2xl overflow-hidden shadow-lg">

                <img
            src="https://picsum.photos/600/400?random=1"
            class="h-60 w-full object-cover"
            >

                <div class="p-6">

                    <h3 class="text-2xl font-bold mb-3">
                        Bantu Korban Banjir
                    </h3>

                    <p class="text-gray-600 mb-5">
                        Mari bantu saudara kita yang terkena bencana banjir.
                    </p>

                    <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl">
                        Donasi Sekarang
                    </button>

                </div>

            </div>

            <!-- CARD 2 -->

            <div class="bg-white rounded-2xl overflow-hidden shadow-lg">

                <img
                src="https://picsum.photos/600/400?random=2"
                class="h-60 w-full object-cover"
                >

                <div class="p-6">

                    <h3 class="text-2xl font-bold mb-3">
                        Pendidikan Anak
                    </h3>

                    <p class="text-gray-600 mb-5">
                        Bantu pendidikan anak-anak kurang mampu.
                    </p>

                    <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl">
                        Donasi Sekarang
                    </button>

                </div>

            </div>

            <!-- CARD 3 -->

            <div class="bg-white rounded-2xl overflow-hidden shadow-lg">

            <img
            src="https://picsum.photos/600/400?random=3"
            class="h-60 w-full object-cover"
            >

               <div class="p-6">

    <h3 class="text-2xl font-bold mb-3">
        Bantu Korban Banjir
    </h3>

    <p class="text-gray-600 mb-5">
        Mari bantu saudara kita yang terkena bencana banjir.
    </p>

    <!-- Progress -->

    <div class="w-full bg-gray-200 rounded-full h-3 mb-3">

        <div class="bg-green-500 h-3 rounded-full w-3/4"></div>

    </div>

    <div class="flex justify-between text-sm mb-5">

        <span class="font-semibold text-green-600">
            Rp 75.000.000
        </span>

        <span class="text-gray-500">
            75%
        </span>

    </div>

    <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl w-full">
        Donasi Sekarang
    </button>

</div>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->

    <footer class="bg-black text-white py-10 text-center">

        <h2 class="text-3xl font-bold mb-4">
            Autopahala
        </h2>

        <p class="text-gray-400">
            Platform donasi online untuk membantu sesama.
        </p>

    </footer>

</body>
</html>