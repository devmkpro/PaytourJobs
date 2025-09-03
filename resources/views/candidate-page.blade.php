<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Candidatura</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ config('app.name') }}
                        </h1>
                        <span class="ml-4 text-sm text-gray-500 dark:text-gray-400 hidden sm:block">
                            Portal de Candidaturas
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/admin" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition font-medium">
                            Área Administrativa
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Hero Section -->
                <div class="text-center mb-8 sm:mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Candidate-se a uma Vaga
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                        Preencha o formulário abaixo para enviar sua candidatura. Nossa equipe analisará suas informações e entrará em contato em breve.
                    </p>
                </div>

                <!-- Form Container -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        @livewire('candidate-application')
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 sm:mt-12 text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                </div>
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
