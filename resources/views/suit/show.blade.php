<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $suit->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let totalExtras = 0; // Total de los servicios seleccionados

            // Función para actualizar el total cuando un checkbox se selecciona o se deselecciona
            $('input[type="checkbox"]').on('change', function() {
                totalExtras = 0; // Resetear el total

                // Iterar sobre cada checkbox seleccionado y sumar el precio
                $('input[type="checkbox"]:checked').each(function() {
                    let price = $(this).data('price'); // Obtener el precio del atributo data-price
                    totalExtras += parseFloat(price); // Sumar al total
                });

                // Actualizar el total de extras en la interfaz
                $('#total-extras').text(totalExtras.toFixed(2)); // Mostrar con 2 decimales
                updateTotal(); // Actualizar el total final
            });

            // Función para actualizar el total general (precio habitación + servicios extras)
            function updateTotal() {
                let total =  totalExtras;
                $('#total-general').text(total.toFixed(2)); // Mostrar el total general con 2 decimales
            }
        });
    </script>
</head>
<body class="bg-gray-100">

    @include('components.navbar') 

    <div class="container mx-auto mt-6 px-4">
        <div class="w-full flex justify-center items-center">
        <img src="{{ $suit->image ?? asset('images/default.png') }}" 
            class="object-cover rounded-lg shadow-md" 
            style="width: 10cm; height: 8cm;">
    </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-3xl font-bold">{{ $suit->name }}</h1>
                <h2 class="text-lg font-semibold text-gray-600">Descripción</h2>
                <p class="text-gray-700 mt-2">{{ $suit->description }}</p>

                <p class="mt-4 text-xl font-semibold">
                    Precio: <span class="text-black"> {{ $first_price }} Bs /Hora</span>
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Agregar Servicios Extras</h2>
                </div>

                    @csrf
                    <div class="space-y-4">
                        @foreach($services as $service)
                            @if($service->is_visible == 1) 
                                <div class="flex items-center">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" id="service-{{ $service->id }}" data-price="{{ $service->price }}" class="mr-2">
                                    <label for="service-{{ $service->id }}" class="text-sm">{{ $service->name }} - {{ $service->price }} Bs</label>
                                </div>
                            @endif
                        @endforeach
                    </div>


                <div class="mt-4 border-t pt-4">
                       <p><strong>Total Habitacion:</strong> <span >{{ $first_price }} </span> Bs</p>
                    <p><strong>Total Extras:</strong> <span id="total-extras">0.00</span> Bs</p>
                    <p class="text-xl font-bold"><strong>Total:</strong> <span id="total-general"></span></p>
                </div>
            </div>
        </div>
    </div>
    @include('reservation.register')
</body>
</html>
