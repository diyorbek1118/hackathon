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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css', 'resources/js/main.js', 'resources/js/forecast.js', 'resources/js/statistics.js'])

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

    {{ $slot }}

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
</body>

</html>
