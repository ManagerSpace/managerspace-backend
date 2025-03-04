<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $project->name }}</h3>
                        <div>
                            <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:border-yellow-800 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition mr-2">
                                Edit
                            </a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition" onclick="return confirm('Are you sure you want to delete this project?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Project Information</h4>
                            <p><strong>Client:</strong> {{ $project->client->name }}</p>
                            <p><strong>Start Date:</strong> {{ $project->start_date->format('Y-m-d') }}</p>
                            <p><strong>End Date:</strong> {{ $project->end_date->format('Y-m-d') }}</p>
                            <p><strong>Status:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $project->status === 'completed' ? 'green' : ($project->status === 'in_progress' ? 'yellow' : 'red') }}-100 text-{{ $project->status === 'completed' ? 'green' : ($project->status === 'in_progress' ? 'yellow' : 'red') }}-800">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </p>
                            <p><strong>Budget:</strong> ${{ number_format($project->budget, 2) }}</p>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Description</h4>
                            <p>{{ $project->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
