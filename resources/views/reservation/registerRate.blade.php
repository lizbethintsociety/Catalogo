
<div class="container mx-auto mt-6 px-4">
        <h1 class="text-3xl font-bold mb-4">Reservar Habitación</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg">
        {{--
             form action="{{ route('reservation.store') }}" method="POST">--}}
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold">Nombre</label>
                    <input type="text" id="name" name="name" class="w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Tu nombre" required>
                </div>

                <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-semibold">Teléfono</label>
                        <input type="tel" id="phone" name="phone" class="w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Tu número de teléfono" required>
                    </div>

                    <div>
                        <label for="alternate_phone" class="block text-sm font-semibold">Teléfono Alternativo</label>
                        <input type="tel" id="alternate_phone" name="alternate_phone" class="w-full mt-2 p-2 border border-gray-300 rounded-lg" placeholder="Teléfono alternativo (opcional)">
                    </div>
                </div>         
                <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                    <label for="reservation_date" class="block text-sm font-semibold">Fecha de Reserva</label>
                    <input type="date" id="reservation_date" name="reservation_date" class="w-full mt-2 p-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                    <label for="reservation_time" class="block text-sm font-semibold">Hora de Reserva</label>
                    <input type="time" id="reservation_time" name="reservation_time" class="w-full mt-2 p-2 border border-gray-300 rounded-lg" required>
                    </div>
                </div>
                <div class="mb-4">
            <label for="room_id" class="block text-sm font-semibold">Seleccionar Habitación</label>
            <select id="room_id" name="room_id" class="w-full mt-2 p-2 border border-gray-300 rounded-lg" required>
                <option value="">Seleccione una habitación</option>
                @foreach($suits as $suit)
                    <option value="{{ $suit->id }}">{{ $suit->name }} - {{ $suit->description }}</option>
                @endforeach
            </select>
        </div>



                <div class="mt-6">
                    <button type="submit" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Reservar Habitación
                    </button>
                </div>
                {{--        </form> --}}
        </div>
    </div>

