<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Nuevo Barbero') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Teléfono / Celular')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="specialty" :value="__('Especialidad (Ej: Barba, Degradados)')" />
                        <x-text-input id="specialty" class="block mt-1 w-full" type="text" name="specialty" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Contraseña Temporal')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.panel') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancelar</a>
                        
                        <x-primary-button class="ms-4">
                            {{ __('Registrar Barbero') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
