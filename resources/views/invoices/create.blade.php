<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="company_id" value="{{ __('Company') }}" />
                                <select id="company_id" name="company_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="client_id" value="{{ __('Client') }}" />
                                <select id="client_id" name="client_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="project_id" value="{{ __('Project') }}" />
                                <select id="project_id" name="project_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option value="">No Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="issue_date" value="{{ __('Issue Date') }}" />
                                <x-input id="issue_date" class="block mt-1 w-full" type="date" name="issue_date" :value="old('issue_date')" required />
                            </div>

                            <div>
                                <x-label for="status" value="{{ __('Status') }}" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <x-label for="notes" value="{{ __('Notes') }}" />
                                <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Products</h3>
                            <div id="products-container">
                                <!-- Product rows will be added here dynamically -->
                            </div>
                            <button type="button" id="add-product" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Product</button>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Create Invoice') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productCount = 0;

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const productRow = document.createElement('div');
            productRow.classList.add('grid', 'grid-cols-6', 'gap-4', 'mt-4');
            productRow.innerHTML = `
                <div>
                    <x-label for="products[${productCount}][category_id]" value="Category" />
                    <select id="products[${productCount}][category_id]" name="products[${productCount}][category_id]" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
            </select>
        </div>
        <div>
            <x-label for="products[${productCount}][name]" value="Product Name" />
                    <x-input id="products[${productCount}][name]" class="block mt-1 w-full" type="text" name="products[${productCount}][name]" required />
                </div>
                <div>
                    <x-label for="products[${productCount}][description]" value="Description" />
                    <x-input id="products[${productCount}][description]" class="block mt-1 w-full" type="text" name="products[${productCount}][description]" />
                </div>
                <div>
                    <x-label for="products[${productCount}][price]" value="Price" />
                    <x-input id="products[${productCount}][price]" class="block mt-1 w-full" type="number" name="products[${productCount}][price]" step="0.01" required />
                </div>
                <div>
                    <x-label for="products[${productCount}][tax_id]" value="Tax" />
                    <select id="products[${productCount}][tax_id]" name="products[${productCount}][tax_id]" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                        @foreach($taxes as $tax)
            <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                        @endforeach
            </select>
        </div>
        <div>
            <x-label for="products[${productCount}][quantity]" value="Quantity" />
                    <x-input id="products[${productCount}][quantity]" class="block mt-1 w-full" type="number" name="products[${productCount}][quantity]" required />
                </div>
                <div class="col-span-6">
                    <button type="button" class="mt-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 remove-product">Remove</button>
                </div>
            `;
            container.appendChild(productRow);
            productCount++;

            productRow.querySelector('.remove-product').addEventListener('click', function() {
                container.removeChild(productRow);
            });
        });
    </script>
</x-app-layout>
