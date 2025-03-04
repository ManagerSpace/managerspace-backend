<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Company') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <form action="{{ route('companies.update', $company) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="name" value="{{ __('Company Name') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $company->name)" required autofocus />
                            </div>

                            <div>
                                <x-label for="tax_id" value="{{ __('Tax ID') }}" />
                                <x-input id="tax_id" class="block mt-1 w-full" type="text" name="tax_id" :value="old('tax_id', $company->tax_id)" required />
                            </div>

                            <div>
                                <x-label for="address" value="{{ __('Address') }}" />
                                <x-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $company->address)" required />
                            </div>

                            <div>
                                <x-label for="phone" value="{{ __('Phone') }}" />
                                <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $company->phone)" required />
                            </div>

                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $company->email)" required />
                            </div>

                            <div>
                                <x-label for="website" value="{{ __('Website') }}" />
                                <x-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website', $company->website)" />
                            </div>

                            <div>
                                <x-label for="invoice_prefix" value="{{ __('Invoice Prefix') }}" />
                                <x-input id="invoice_prefix" class="block mt-1 w-full" type="text" name="invoice_prefix" :value="old('invoice_prefix', $company->invoice_prefix)" required />
                            </div>

                            <div>
                                <x-label for="invoice_start_number" value="{{ __('Invoice Start Number') }}" />
                                <x-input id="invoice_start_number" class="block mt-1 w-full" type="number" name="invoice_start_number" :value="old('invoice_start_number', $company->invoice_start_number)" required />
                            </div>

                            <div>
                                <x-label for="logo" value="{{ __('Company Logo') }}" />
                                <input id="logo" class="block mt-1 w-full" type="file" name="logo" accept="image/*" />
                            </div>

                            @if($company->logo_path)
                                <div>
                                    <p class="mb-2">Current Logo:</p>
                                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }} logo" class="max-w-xs">
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Update Company') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
