<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Barbería Tilli</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased bg-gray-900 text-white">
        
        <nav class="flex items-center justify-between p-6 bg-gray-800">
            <div class="text-2xl font-bold tracking-widest uppercase">
                💈 Barbería Tilli
            </div>
            <div>
                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-300 hover:text-white">Mi Cuenta</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Iniciar Sesión</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <div class="relative bg-gray-800 overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-gray-800 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Tu estilo,</span>
                                <span class="block text-yellow-500 xl:inline">nuestra pasión.</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Reserva tu cita en segundos. Los mejores barberos de la ciudad están listos para darte el corte que mereces.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-black bg-yellow-500 hover:bg-yellow-600 md:py-4 md:text-lg md:px-10">
                                        Reservar Cita
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-1.2.1&auto=format&fit=crop&w=1953&q=80" alt="Barber Shop">
            </div>
        </div>

        <div class="py-12 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-yellow-500 font-semibold tracking-wide uppercase">Catálogo</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-white sm:text-4xl">
                        Nuestros Servicios
                    </p>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($services as $service)
                        <div class="bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-700 flex flex-col">
                            <div class="h-48 w-full bg-gray-700 overflow-hidden">
                                @if(isset($service->image))
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                @else
                                    <img src="https://images.unsplash.com/photo-1503951914875-452162b7f304?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Corte Genérico" class="w-full h-full object-cover opacity-50">
                                @endif
                            </div>

                            <div class="px-4 py-5 sm:p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-white">
                                        {{ $service->name }}
                                    </h3>
                                    <div class="mt-2 max-w-xl text-sm text-gray-400">
                                        <p>Duración aprox: {{ $service->duration_min }} min</p>
                                    </div>
                                </div>
                                
                                <div class="mt-5 flex items-center justify-between">
                                    <span class="text-2xl font-bold text-yellow-500">
                                        ${{ $service->price }}
                                    </span>
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-yellow-500 hover:text-yellow-400 border border-yellow-500 px-3 py-1 rounded hover:bg-yellow-500 hover:text-black transition">
                                        Agendar &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12 bg-gray-800 border-t border-gray-700">
            <div class="max-w-3xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-white text-center mb-8">Contáctanos</h2>
                
                @if(session('status'))
                    <div class="bg-green-500 text-white p-4 rounded mb-6 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Tu Nombre" required class="w-full p-3 rounded bg-gray-900 text-white border border-gray-600 focus:border-yellow-500 outline-none">
                        <input type="email" name="email" placeholder="Tu Email" required class="w-full p-3 rounded bg-gray-900 text-white border border-gray-600 focus:border-yellow-500 outline-none">
                    </div>
                    <textarea name="message" rows="4" placeholder="¿En qué podemos ayudarte?" required class="w-full p-3 rounded bg-gray-900 text-white border border-gray-600 focus:border-yellow-500 outline-none"></textarea>
                    
                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 rounded transition">
                        Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>

        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">
                        &copy; 2025 Barbería Tilli. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
