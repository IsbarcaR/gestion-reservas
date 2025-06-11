<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestión de Reservas</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100 dark:bg-gray-900">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 text-center">
                
                <div class="mx-auto flex items-center justify-center h-16 w-16 bg-indigo-100 dark:bg-indigo-900 rounded-full mb-6">
                    <svg class="h-9 w-9 text-indigo-600 dark:text-indigo-300" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7C4 5.89543 4.89543 5 6 5H18C19.1046 5 20 5.89543 20 7V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V7Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M16 3V6M8 3V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M4 10H20" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M9.5 15.5L11.5 17.5L15.5 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Gestión de Reservas
                </h1>
                <p class="mt-3 text-base text-gray-600 dark:text-gray-400">
                    Bienvenido. Accede a tu cuenta o regístrate para comenzar a gestionar tus citas.
                </p>

                <div class="mt-8">
                    @if (Route::has('login'))
                        <div class="flex flex-col space-y-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    Ir al Panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    Iniciar Sesión
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </body>
</html>