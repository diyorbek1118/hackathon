<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prognoz - CashFlow AI</title>
    <meta name="description" content="30-90 kunlik pul oqimi prognozi va AI maslahatlar">
    <link rel="icon" href="https://public-frontend-cos.metadl.com/mgx/img/favicon-atoms.png" type="image/png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

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
                    <a href="{{ route('pages.main') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Asosiy</a>
                    <a href="{{ route('pages.statistics') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">Statistika</a>
                    <a href="{{ route('pages.forecast') }}"
                        class="text-blue-600 dark:text-blue-400 font-medium">Prognoz</a>

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
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2">Asosiy</a>
                    <a href="{{ route('pages.statistics') }}"
                        class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2">Statistika</a>
                    <a href="{{ route('pages.forecast') }}"
                        class="text-blue-600 dark:text-blue-400 font-medium py-2">Prognoz</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-2">
                    Pul Oqimi <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">Prognozi</span>
                </h1>
                <p class="text-slate-600 dark:text-slate-400">AI yordamida 30-90 kunlik prognoz va tavsiyalar</p>
            </div>

            <!-- Forecast Period Selector -->
            <div class="mb-8 flex flex-wrap gap-4">
                <button onclick="updateForecastPeriod(30)" id="btn-30"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg font-medium transition-all hover:bg-blue-600">
                    30 kun
                </button>
                <button onclick="updateForecastPeriod(60)" id="btn-60"
                    class="px-6 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-medium transition-all hover:bg-slate-300 dark:hover:bg-slate-700">
                    60 kun
                </button>
                <button onclick="updateForecastPeriod(90)" id="btn-90"
                    class="px-6 py-2 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-medium transition-all hover:bg-slate-300 dark:hover:bg-slate-700">
                    90 kun
                </button>
            </div>

            <!-- Risk Alert -->
            <div
                class="mb-8 bg-gradient-to-r from-amber-500/10 to-orange-500/10 border-l-4 border-amber-500 dark:border-amber-400 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Diqqat: Likvidlik Xavfi
                            Aniqlandi</h3>
                        <p class="text-slate-700 dark:text-slate-300 mb-3">AI tahlilimiz 45 kun ichida pul oqimi
                            inqirozi ehtimolini aniqladi. Xavf darajasi: <span
                                class="font-bold text-amber-600 dark:text-amber-400">O'rta</span></p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                                <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-3 rounded-full"
                                    style="width: 65%"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">65%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forecast Chart -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Prognoz Diagrammasi</h3>
                <canvas id="forecastChart"></canvas>
            </div>

            <!-- AI Recommendations -->
            <div class="grid lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">AI Tavsiyalari</h3>
                    </div>

                    <div class="space-y-4">
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">1</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Xarajatlarni Kamaytiring
                                    </h4>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">Operatsion xarajatlarni 15%
                                        ga qisqartirish tavsiya etiladi. Bu oyiga 4.2M so'm tejashga olib keladi.</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">2</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Qarz Olish</h4>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">Bank qarzini 25M so'm
                                        miqdorida olish likvidlik muammosini hal qiladi. Foiz stavkasi: 18% yillik.</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">3</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">To'lovlarni Tezlashtiring
                                    </h4>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">Mijozlardan to'lovlarni 10
                                        kun erta olish orqali pul oqimini yaxshilang. Jami: 18M so'm.</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-white text-xs font-bold">4</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Inventarni
                                        Optimallashtiring</h4>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">Ortiqcha inventarni sotish
                                        orqali 12M so'm qo'shimcha pul oqimi yarating.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Chat Assistant -->
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">AI Maslahat</h3>
                    </div>

                    <div id="chat-messages" class="flex-1 space-y-4 mb-4 overflow-y-auto max-h-96 pr-2">
                        <div class="flex gap-3">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1 bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-none p-4">
                                <p class="text-sm text-slate-700 dark:text-slate-300">Salom! Men sizning moliyaviy
                                    maslahatchi AI assistentingizman. Qanday yordam bera olaman?</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <input type="text" id="chat-input" placeholder="Savolingizni yozing..."
                            class="flex-1 px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="sendMessage()"
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white rounded-xl font-medium transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Jo'natish
                        </button>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button onclick="quickQuestion('Qanday qilib xarajatlarni kamaytirishim mumkin?')"
                            class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-lg transition-colors">
                            Xarajatlarni kamaytirish
                        </button>
                        <button onclick="quickQuestion('Qaysi qarzni birinchi bo\'lib to\'lashim kerak?')"
                            class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-lg transition-colors">
                            Qarz strategiyasi
                        </button>
                        <button onclick="quickQuestion('Investitsiya qilish uchun eng yaxshi vaqt qachon?')"
                            class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-lg transition-colors">
                            Investitsiya
                        </button>
                    </div>
                </div>
            </div>

            <!-- Confidence Metrics -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-slate-900 dark:text-white">Prognoz Aniqligi</h4>
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">98%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full"
                            style="width: 98%"></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-slate-900 dark:text-white">Ishonch Darajasi</h4>
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">92%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-3 rounded-full" style="width: 92%">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-slate-900 dark:text-white">Ma'lumot Sifati</h4>
                        <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">95%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full" style="width: 95%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer
        class="bg-slate-100 dark:bg-slate-950 text-slate-700 dark:text-slate-300 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                <p>© 2026 CashFlow AI. Barcha huquqlar himoyalangan.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/forecast.js') }}"></script>
</body>

</html>
