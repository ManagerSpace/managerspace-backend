<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Ingreso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Ingreso #{{ $income->id }}</h3>
                        <div>
                            <a href="{{ route('incomes.edit', $income) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:border-yellow-800 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition mr-2">
                                Editar
                            </a>
                            <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition" onclick="return confirm('¿Estás seguro de que quieres eliminar este ingreso?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Información General</h4>
                            <p><strong>Categoría:</strong> {{ $income->category->name }}</p>
                            <p><strong>Monto:</strong> {{ number_format($income->amount, 2) }} €</p>
                            <p><strong>Fecha:</strong> {{ $income->date->format('d/m/Y') }}</p>
                            <p><strong>Descripción:</strong> {{ $income->description ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Información de Recurrencia</h4>
                            <p><strong>Es Recurrente:</strong> {{ $income->is_recurring ? 'Sí' : 'No' }}</p>
                            @if($income->is_recurring)
                                <p><strong>Frecuencia de Recurrencia:</strong> {{ ucfirst($income->recurrence_frequency) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
