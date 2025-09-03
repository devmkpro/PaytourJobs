<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Candidatura - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Filament Styles -->
    @filamentStyles
    @vite('resources/css/app.css')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 h-full">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ config('app.name', 'Laravel') }}
                        </h1>
                    </div>
                  
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                        Candidate-se a uma Vaga
                    </h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                        Preencha o formulário abaixo para enviar sua candidatura. Nossa equipe analisará suas informações e entrará em contato em breve.
                    </p>
                </div>

                <!-- Form Container -->
                <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                    <div class="px-4 py-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Dúvidas? Entre em contato conosco através do email 
                        <a href="mailto:rh@empresa.com" class="text-blue-600 hover:text-blue-500">
                            rh@empresa.com
                        </a>
                    </p>
                </div>
            </div>
        </main>
    </div>

    <!-- Filament Scripts -->
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
