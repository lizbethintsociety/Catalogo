@section('content')
    <div class="container">
        @if($producto)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $producto->name }}</h5>

                    <p class="card-text">{{ $producto->description ?? 'Descripción no disponible' }}</p>

                    <p><strong>Precio: </strong>${{ $producto->precio ?? 'No disponible' }}</p> 
                </div>
            </div>
        @else
            <p>Producto no encontrado.</p>
        @endif
    </div>
@endsection
