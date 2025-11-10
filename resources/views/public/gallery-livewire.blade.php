<x-layouts.public>
    <x-slot name="title">Gallery | Hon. Fatimatu Abubakar</x-slot>

    <!-- Page Header -->
    <div class="bg-[#003366] text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" style="font-family: 'Playfair Display', serif;">Gallery</h1>
            <p class="text-xl text-gray-300">Visual documentation of work and events</p>
        </div>
    </div>

    <!-- Livewire Gallery Filter Component -->
    @livewire('gallery-filter', ['category' => request()->get('category', 'all')])
</x-layouts.public>
