<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl">
                        {{ $user->name }}
                    </div>

                    <div class="mt-6 text-gray-500">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Rol:</strong> {{ ucfirst($user->role) }}</p>
                        <p><strong>Fecha de Registro:</strong> {{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Editar Usuario
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
