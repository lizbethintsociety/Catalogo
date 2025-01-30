<!-- resources/views/catalogo/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de Productos</title>
</head>
<body>
    <h1>Catálogo de Productos</h1>

    <!-- Verifica si existen productos -->
    @if($productos->isEmpty())
        <p>No hay productos disponibles.</p>
    @else
        <ul>
            <!-- Recorre y muestra cada producto -->
            @foreach($productos as $producto)
                <li>
                    <a href="{{ route('catalogo.show', $producto->id) }}">
                        {{ $producto->name }} <!-- Aquí se usa 'name' como ejemplo, ajusta al campo que tengas -->
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
