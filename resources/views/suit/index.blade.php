<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Habitaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    @include('components.navbar') 

    <div class="container mx-auto mt-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Nuestras Habitaciones</h1>

        @if($suits->isEmpty())
            <p class="text-gray-500">No hay habitaciones disponibles.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($suits as $suit)
                    <div class="relative bg-white shadow-md rounded-lg overflow-hidden group" 
                         style="width: 10cm; height: 6.5cm;">
                         
                        <img src="{{ asset('images/' . ($suit->image ?? 'default.png')) }}" 
                             alt="{{ $suit->name }}" 
                             class="w-full h-40 object-cover">

                        <div class="p-4">
                            <h2 class="text-lg font-semibold">{{ $suit->name }}</h2>
                            {{-- <p class="text-sm text-gray-600 truncate">{{ $suit->description }}</p> --}}
                        </div>

                        <a href="{{ route('suit.show', $suit->id) }}" 
                           class="absolute bottom-0 left-0 w-full bg-blue-600 text-white text-center py-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Ver Habitación
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
