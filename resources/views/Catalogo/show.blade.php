@section('content')
    <div class="container">
        <!-- Verificar si el producto está presente -->
        @if($producto)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $producto->name }}</h5>

                    <!-- Mostrar descripción o mensaje si es nula -->
                    <p class="card-text">{{ $producto->description ?? 'Descripción no disponible' }}</p>

                    <!-- Mostrar precio, si existe -->
                    <p><strong>Precio: </strong>${{ $producto->precio ?? 'No disponible' }}</p>
                </div>
            </div>
        @else
            <p>Producto no encontrado.</p>
        @endif
    </div>
@endsection
