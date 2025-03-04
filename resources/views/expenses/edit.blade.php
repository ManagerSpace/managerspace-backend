<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Gasto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <form action="{{ route('expenses.update', $expense) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="category_id" value="{{ __('Categoría') }}" />
                                <select name="category_id" id="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="id_company" value="{{ __('Compañía') }}" />
                                <select name="id_company" id="id_company" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('id_company', $expense->id_company) == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="amount" value="{{ __('Monto') }}" />
                                <x-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount', $expense->amount)" required step="0.01" />
                            </div>

                            <div>
                                <x-label for="date" value="{{ __('Fecha') }}" />
                                <x-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $expense->date->format('Y-m-d'))" required />
                            </div>

                            <div class="col-span-2">
                                <x-label for="description" value="{{ __('Descripción') }}" />
                                <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3">{{ old('description', $expense->description) }}</textarea>
                            </div>

                            <div class="col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_recurring" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_recurring', $expense->is_recurring) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Es Recurrente') }}</span>
                                </label>
                            </div>

                            <div>
                                <x-label for="recurrence_frequency" value="{{ __('Frecuencia de Recurrencia') }}" />
                                <select name="recurrence_frequency" id="recurrence_frequency" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">Seleccionar frecuencia</option>
                                    <option value="weekly" {{ old('recurrence_frequency', $expense->recurrence_frequency) == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                    <option value="monthly" {{ old('recurrence_frequency', $expense->recurrence_frequency) == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                    <option value="yearly" {{ old('recurrence_frequency', $expense->recurrence_frequency) == 'yearly' ? 'selected' : '' }}>Anual</option>
                                </select>
                            </div>

                            <div class="flex items-center">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="tax_deductible" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('tax_deductible', $expense->tax_deductible) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Deducible de Impuestos') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button>
                                {{ __('Actualizar Gasto') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
