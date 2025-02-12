<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $suit->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    @include('components.navbar') <!-- Navbar -->

    <div class="container mx-auto mt-6 px-4">
        <!-- Imagen Grande (Ocupando toda la fila) -->
        <div class="w-full">
            <img src="{{ asset('images/' . ($suit->image ?? 'default.png')) }}" 
                 class="w-full h-80 object-cover rounded-lg shadow-md">
        </div>

        <!-- Sección Dividida en Dos Columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Columna Izquierda: Nombre y Descripción -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-3xl font-bold">{{ $suit->name }}</h1>
                <h2 class="text-lg font-semibold text-gray-600">Descripción</h2>
                <p class="text-gray-700 mt-2">{{ $suit->description }}</p>

                <p class="mt-4 text-xl font-semibold">
                    Precio: <span class="text-black">/Hora</span>
                </p>
            </div>

            <!-- Columna Derecha: Extras y Total -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <!-- Botón para agregar extras -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Agregar Servicios Extras</h2>
                    <button class="px-4 py-2 bg-gray-300 text-black rounded-lg flex items-center gap-2 hover:bg-gray-400">
                        Agregar Extra <span class="text-xl font-bold">+</span>
                    </button>
                </div>

                <!-- Tabla de Extras -->
                {{-- <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="font-semibold">Nombre</th>
                            <th class="font-semibold">Costo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Decoraciones</td>
                            <td>50 Bs</td>
                        </tr>
                        <tr>
                            <td>Desayuno</td>
                            <td>60 Bs</td>
                        </tr>
                    </tbody>
                </table> --}}

                <!-- Totales -->
                <div class="mt-4 border-t pt-4">
                    <p><strong>Total Extras:</strong> </p>
                    <p><strong>Total Habitación:</strong> </p>
                    <p class="text-xl font-bold"><strong>Total:</strong> </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
