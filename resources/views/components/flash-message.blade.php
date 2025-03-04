@if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-b shadow-lg">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-4 py-2 rounded-b shadow-lg">
        <p>{{ session('error') }}</p>
    </div>
@endif
