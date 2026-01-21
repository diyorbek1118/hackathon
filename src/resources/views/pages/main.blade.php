<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CashFlow AI - Pul oqimi inqirozlarini prognozlash</title>
    <meta name="description"
        content="AI yordamida 30-90 kun oldidan pul oqimi inqirozlarini aniqlang. KO'B, banklar va moliyaviy institutlar uchun maxsus prognoz tizimi.">
    <link rel="icon" href="https://public-frontend-cos.metadl.com/mgx/img/favicon-atoms.png" type="image/png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            900: '#0f172a'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <!-- Navbar -->
    <nav
        class="fixed top-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <a href="{{ route('pages.main') }}"
                        class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">
                        CashFlow AI
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('pages.main') }}" class="text-blue-600 dark:text-blue-400 font-medium">Asosiy</a>
                    <a href="{{ route('pages.statistics') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Statistika</a>
                    <a href="{{ route('pages.forecast') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Prognoz</a>
                    <a href="#pricing"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Narxlar</a>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle"
                        class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <button id="mobile-menu-button" class="md:hidden text-slate-700 dark:text-slate-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-slate-200 dark:border-slate-800 mt-2">
                <div class="flex flex-col space-y-3 pt-4">
                    <a href="{{ route('pages.main') }}"
                        class="text-blue-600 dark:text-blue-400 font-medium py-2">Asosiy</a>
                    <a href="{{ route('pages.statistics') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2">Statistika</a>
                    <a href="{{ route('pages.forecast') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2">Prognoz</a>
                    <a href="#pricing"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2">Narxlar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section
        class="relative pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 overflow-hidden transition-colors duration-300">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-500 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1s;"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-block">
                        <span
                            class="px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-600 dark:text-blue-400 text-sm font-medium">
                            AI-Powered Moliyaviy Prognoz
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white leading-tight">
                        Pul oqimi inqirozlarini
                        <span
                            class="block bg-gradient-to-r from-blue-600 via-cyan-600 to-blue-600 dark:from-blue-400 dark:via-cyan-400 dark:to-blue-400 bg-clip-text text-transparent">
                            oldindan ko'ring
                        </span>
                    </h1>

                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 leading-relaxed">
                        Sun'iy intellekt yordamida 30-90 kun oldidan likvidlik muammolarini aniqlang. KO'B uchun maxsus
                        ishlab chiqilgan prognoz tizimi.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('pages.statistics') }}"
                            class="px-8 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white rounded-lg font-medium transition-all flex items-center justify-center group">
                            Statistikani ko'ring
                            <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="{{ route('pages.forecast') }}"
                            class="px-8 py-3 border-2 border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg font-medium transition-all flex items-center justify-center">
                            Prognoz olish
                        </a>
                    </div>

                    <div class="flex items-center gap-8 pt-4">
                        <div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">98%</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Aniqlik</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">500+</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Bizneslar</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">24/7</div>
                            <div class="text-sm text-slate-600 dark:text-slate-400">Monitoring</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative z-10">
                        <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/47932082-8054-48c8-9c38-83461f9339af.png"
                            alt="CashFlow AI Dashboard"
                            class="w-full rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 md:py-24 bg-white dark:bg-slate-950 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    Asosiy
                    <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">
                        imkoniyatlar</span>
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                    CashFlow AI platformasi sizning biznesingizni moliyaviy inqirozlardan himoya qilish uchun zamonaviy
                    texnologiyalardan foydalanadi
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div
                    class="group bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 p-0.5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <div
                                class="w-full h-full rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/6c710f29-8199-47c1-89c1-221ce612d08d.png"
                                    alt="AI Prognoz" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                AI Prognoz Tizimi
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Mashinali o'rganish
                                algoritmlari yordamida 30-90 kun oldidan pul oqimi prognozi. 98% aniqlik darajasi.</p>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 p-0.5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <div
                                class="w-full h-full rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/505fa768-c5a1-4094-bc15-4fdf6c503bb1.png"
                                    alt="Ogohlantirish" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                Dastlabki Ogohlantirish
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Likvidlik stress signallarini
                                real vaqtda aniqlash. Muammolar katta bo'lishidan oldin xabardor bo'ling.</p>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-500 p-0.5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <div
                                class="w-full h-full rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/b23a7d67-1290-40f5-803e-d06e066de9c9.png"
                                    alt="Tavsiyalar" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                Amaliy Tavsiyalar
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Har bir vaziyat uchun aniq va
                                amaliy yechimlar. AI sizga qanday harakat qilish kerakligini ko'rsatadi.</p>
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 p-0.5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <div
                                class="w-full h-full rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/71b23aee-9d42-498a-95c0-9199c8889aad.png"
                                    alt="Avtomatlashtirish" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                Avtomatlashtirish
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Inson omilini
                                minimallashtiring. Avtomatik monitoring, tahlil va hisobotlar 24/7 rejimida.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 md:py-24 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    Qanday
                    <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">
                        ishlaydi</span>?
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                    To'rtta oddiy qadam bilan biznesingiz uchun aniq moliyaviy prognoz oling
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="relative group">
                    <div
                        class="relative bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:border-blue-500/50 hover:shadow-xl transition-all duration-300">
                        <div
                            class="absolute -top-4 left-6 w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            01
                        </div>
                        <div class="mt-8 mb-4 flex justify-center">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/75f34494-cf5c-497a-b7d2-f129019246dd.png"
                                    alt="Upload" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 text-center">Ma'lumotlarni
                            yuklang</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed text-center">Bank
                            hisobotlari, fakturalar va boshqa moliyaviy ma'lumotlarni tizimga yuklang.</p>
                    </div>
                </div>

                <div class="relative group">
                    <div
                        class="relative bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:border-blue-500/50 hover:shadow-xl transition-all duration-300">
                        <div
                            class="absolute -top-4 left-6 w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            02
                        </div>
                        <div class="mt-8 mb-4 flex justify-center">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/71b23aee-9d42-498a-95c0-9199c8889aad.png"
                                    alt="AI Analysis" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 text-center">AI tahlil qiladi
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed text-center">Sun'iy
                            intellekt ma'lumotlaringizni tahlil qiladi va kelajakni prognoz qiladi.</p>
                    </div>
                </div>

                <div class="relative group">
                    <div
                        class="relative bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:border-blue-500/50 hover:shadow-xl transition-all duration-300">
                        <div
                            class="absolute -top-4 left-6 w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            03
                        </div>
                        <div class="mt-8 mb-4 flex justify-center">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/7870a8d2-03b4-4a5a-bcb1-4cbb90c1cb50.png"
                                    alt="Forecast" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 text-center">Prognoz oling
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed text-center">30-90 kunlik
                            pul oqimi prognozi va xavf darajalari haqida hisobot.</p>
                    </div>
                </div>

                <div class="relative group">
                    <div
                        class="relative bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:border-blue-500/50 hover:shadow-xl transition-all duration-300">
                        <div
                            class="absolute -top-4 left-6 w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            04
                        </div>
                        <div class="mt-8 mb-4 flex justify-center">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <img src="https://mgx-backend-cdn.metadl.com/generate/images/793267/2026-01-20/b23a7d67-1290-40f5-803e-d06e066de9c9.png"
                                    alt="Action" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 text-center">Harakat qiling
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed text-center">Amaliy
                            tavsiyalar asosida to'g'ri qarorlar qabul qiling.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-16 md:py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    Tarif
                    <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">
                        rejalari</span>
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                    Biznesingiz hajmiga mos keladigan rejani tanlang
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-7xl mx-auto">
                <div
                    class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-500/50 rounded-2xl p-8 transition-all duration-300">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Starter</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">Kichik bizneslar uchun</p>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-slate-900 dark:text-white">299,000</span>
                            <span class="text-slate-600 dark:text-slate-400 ml-2">so'm</span>
                            <div class="text-slate-600 dark:text-slate-400 mt-1">oyiga</div>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">30 kunlik prognoz</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">Asosiy ogohlantirish</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">1 ta foydalanuvchi</span>
                        </li>
                    </ul>
                    <button
                        class="w-full px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-all">
                        Boshlash
                    </button>
                </div>

                <div
                    class="relative bg-slate-50 dark:bg-slate-800/50 border-2 border-blue-500/50 rounded-2xl p-8 transform md:scale-105 shadow-2xl shadow-blue-500/20">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span
                            class="px-4 py-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-sm font-semibold rounded-full">
                            Mashhur
                        </span>
                    </div>
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Professional</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">O'rta bizneslar uchun</p>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-slate-900 dark:text-white">699,000</span>
                            <span class="text-slate-600 dark:text-slate-400 ml-2">so'm</span>
                            <div class="text-slate-600 dark:text-slate-400 mt-1">oyiga</div>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">90 kunlik prognoz</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">Kengaytirilgan tahlil</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">5 ta foydalanuvchi</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">API integratsiya</span>
                        </li>
                    </ul>
                    <button
                        class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white rounded-lg font-medium transition-all">
                        Boshlash
                    </button>
                </div>

                <div
                    class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-500/50 rounded-2xl p-8 transition-all duration-300">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Enterprise</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">Katta tashkilotlar uchun</p>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-slate-900 dark:text-white">Kelishilgan</span>
                            <div class="text-slate-600 dark:text-slate-400 mt-1">narx</div>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">Cheksiz prognoz</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">Maxsus AI model</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700 dark:text-slate-300 text-sm">24/7 qo'llab-quvvatlash</span>
                        </li>
                    </ul>
                    <button
                        class="w-full px-6 py-3 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-all">
                        Bog'lanish
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-blue-600 via-cyan-600 to-blue-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div
                class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.1),transparent_50%)]">
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Biznesingizni moliyaviy inqirozlardan himoya qiling
                </h2>

                <p class="text-lg md:text-xl text-blue-100 mb-8 leading-relaxed">
                    Bugun CashFlow AI bilan boshlang va pul oqimi muammolarini oldindan ko'ring. 14 kunlik bepul sinov
                    davri.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="{{ route('pages.statistics') }}"
                        class="px-8 py-3 bg-white text-blue-600 hover:bg-gray-100 font-semibold rounded-lg transition-all flex items-center group">
                        Bepul sinab ko'ring
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('pages.forecast') }}"
                        class="px-8 py-3 border-2 border-white text-white hover:bg-white/10 font-semibold rounded-lg transition-all">
                        Prognoz ko'rish
                    </a>
                </div>

                <div class="flex flex-wrap justify-center gap-8 text-blue-100">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                        <span>Kredit karta kerak emas</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                        <span>14 kun bepul sinov</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer
        class="bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <a href="{{ route('pages.main') }}"
                        class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent mb-4 inline-block">
                        CashFlow AI
                    </a>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">
                        Sun'iy intellekt yordamida pul oqimi inqirozlarini oldindan ko'ring.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Sahifalar</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('pages.main') }}"
                                class="hover:text-blue-600 dark:hover:text-blue-400">Asosiy</a></li>
                        <li><a href="{{ route('pages.statistics') }}"
                                class="hover:text-blue-600 dark:hover:text-blue-400">Statistika</a></li>
                        <li><a href="{{ route('pages.forecast') }}"
                                class="hover:text-blue-600 dark:hover:text-blue-400">Prognoz</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Kompaniya</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-600 dark:hover:text-blue-400">Biz haqimizda</a>
                        </li>
                        <li><a href="#" class="hover:text-blue-600 dark:hover:text-blue-400">Bog'lanish</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Aloqa</h3>
                    <p class="text-sm mb-2"><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                            data-cfemail="f891969e97b89b998b909e94978f9991d68d82">[email&#160;protected]</a></p>
                    <p class="text-sm">+998 (71) 234-56-78</p>
                </div>
            </div>
            <div
                class="border-t border-slate-200 dark:border-slate-800 pt-8 text-center text-sm text-slate-600 dark:text-slate-400">
                <p>© 2026 CashFlow AI. Barcha huquqlar himoyalangan.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    {{-- <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode."></script> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script data-cfasync="false" src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
