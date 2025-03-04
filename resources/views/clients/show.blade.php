<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $client->name }}</h3>
                        <div>
                            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:border-yellow-800 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition mr-2">
                                Editar
                            </a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Información del Cliente</h4>
                            <p><strong>Email:</strong> {{ $client->email }}</p>
                            <p><strong>Teléfono:</strong> {{ $client->phone }}</p>
                            <p><strong>Dirección:</strong> {{ $client->address }}</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Información Adicional</h4>
                            <p><strong>Usuario Asociado:</strong> {{ $client->user->name }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2">Proyectos</h4>
                        @if ($client->projects->count() > 0)
                            <ul class="list-disc list-inside">
                                @foreach($client->projects as $project)
                                    <li>
                                        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $project->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Este cliente no tiene proyectos asociados.</p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2">Facturas</h4>
                        @if($client->invoices->count() > 0)
                            <ul class="list-disc list-inside">
                                @foreach($client->invoices as $invoice)
                                    <li>
                                        <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800">
                                            Factura #{{ $invoice->id }} - {{ $invoice->total }} € ({{ $invoice->status }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>Este cliente no tiene facturas asociadas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
