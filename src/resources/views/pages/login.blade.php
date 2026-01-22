<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish sahifasi - One ID</title>
    <script src="https://cdn.tailwindcss.com"></script>

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

<body class="bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen transition-colors duration-300">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <!-- Toggle tugmasi -->
        <div class="flex justify-end mb-4">
            <button id="darkToggle"
                class="px-3 py-1 rounded-lg bg-primary-600 text-white hover:bg-primary-500 transition">
                🌙 / ☀️
            </button>
        </div>

        <!-- Logo yoki sarlavha -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-primary-600 dark:text-primary-500">Shaxsingizni tasdiqlang</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
                Hisobingizga kirish uchun ma’lumotlarni kiriting
            </p>
        </div>

        <!-- Forma -->
        <form action="/login" method="POST" class="space-y-5">
            <div>
                <input type="text" id="key" name="key" required
                    class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <button type="submit"
                class="w-full bg-primary-600 hover:bg-primary-500 text-white font-semibold py-2 px-4 rounded-lg transition">
                Kirish
            </button>
        </form>

        <div class="mt-6 text-center">
            <button type="submit"
                class="w-full bg-blue-900 hover:bg-primary-500 text-white font-semibold py-2 px-4 rounded-lg transition">
                OneId bilan kirish
            </button>
        </div>
    </div>

    <!-- Dark Mode JS -->
    <script>
        const toggleBtn = document.getElementById('darkToggle');
        toggleBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
        });
    </script>
</body>

</html>
