<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Ingreso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('incomes.update', $income) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-label for="category_id" value="{{ __('Categoría') }}" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $income->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-label for="amount" value="{{ __('Monto') }}" />
                            <x-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount', $income->amount)" required step="0.01" />
                        </div>

                        <div class="mt-4">
                            <x-label for="date" value="{{ __('Fecha') }}" />
                            <x-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $income->date->format('Y-m-d'))" required />
                        </div>

                        <div class="mt-4">
                            <x-label for="description" value="{{ __('Descripción') }}" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('description', $income->description) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="is_recurring" class="inline-flex items-center">
                                <input id="is_recurring" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="is_recurring" {{ $income->is_recurring ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Es recurrente') }}</span>
                            </label>
                        </div>

                        <div class="mt-4">
                            <x-label for="recurrence_frequency" value="{{ __('Frecuencia de recurrencia') }}" />
                            <select id="recurrence_frequency" name="recurrence_frequency" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="">Seleccionar frecuencia</option>
                                <option value="weekly" {{ $income->recurrence_frequency == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                <option value="monthly" {{ $income->recurrence_frequency == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                <option value="yearly" {{ $income->recurrence_frequency == 'yearly' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Actualizar Ingreso') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
