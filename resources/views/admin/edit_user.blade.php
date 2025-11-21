<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Usuario: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$user->name" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$user->email" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="$user->phone" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Rol del Usuario')" />
                        <select name="role" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Cliente</option>
                            <option value="barber" {{ $user->role == 'barber' ? 'selected' : '' }}>Barbero</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="specialty" :value="__('Especialidad (Solo Barberos)')" />
                        <x-text-input id="specialty" class="block mt-1 w-full" type="text" name="specialty" :value="$user->specialty" />
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                        <x-input-label for="password" :value="__('Cambiar Contraseña (Dejar vacío para mantener la actual)')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Nueva contraseña..." />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.panel') }}" class="text-gray-500 mr-4 hover:underline">Cancelar</a>
                        <x-primary-button>
                            {{ __('Guardar Cambios') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
