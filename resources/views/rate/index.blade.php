<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de promociones</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

    @include('components.navbar') 

    <div class="container mx-auto mt-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Nuestras Promociones</h1>

        @if($rates->isEmpty())
            <p class="text-gray-500">No hay promociones disponibles.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($rates as $rate)
                    <div class="relative bg-white shadow-md rounded-lg overflow-hidden group" 
                         style="width: 10cm; height: 6.5cm;">
                         
                        <img src="{{ asset('images/' . ($rate->image ?? 'default.png')) }}" 
                             alt="{{ $rate->name }}" 
                             class="w-full h-40 object-cover">

                        <div class="p-4">
                            <h2 class="text-lg font-semibold">{{ $rate->name }}</h2>
                            {{-- <p class="text-sm text-gray-600 truncate">{{ $rate->description }}</p> --}}
                        </div>

                        <a href="{{ route('rate.show', $rate->id) }}" 
                           class="absolute bottom-0 left-0 w-full bg-blue-600 text-white text-center py-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Ver Promocion
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
