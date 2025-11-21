<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Servicio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('admin.services.update', $service->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nombre del Servicio')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $service->name }}" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Precio ($)')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" value="{{ $service->price }}" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="duration_min" :value="__('Duración (minutos)')" />
                        <x-text-input id="duration_min" class="block mt-1 w-full" type="number" name="duration_min" value="{{ $service->duration_min }}" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Cambiar Foto (Opcional)')" />
                        
                        @if($service->image)
                            <div class="mb-2">
                                <p class="text-sm text-gray-500 mb-1">Foto actual:</p>
                                <img src="{{ asset('storage/' . $service->image) }}" alt="Imagen actual" class="w-20 h-20 object-cover rounded border">
                            </div>
                        @endif

                        <input id="image" class="block mt-1 w-full border border-gray-300 rounded p-2 bg-white dark:bg-gray-900 dark:text-gray-300" type="file" name="image" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.services.index') }}" class="text-gray-500 mr-4">Cancelar</a>
                        <x-primary-button>
                            {{ __('Actualizar Servicio') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
