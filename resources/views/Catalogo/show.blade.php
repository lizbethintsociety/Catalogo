<!-- resources/views/catalogo/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <img src="{{ asset('storage/'.$producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
            <div class="card-body">
                <h5 class="card-title">{{ $producto->nombre }}</h5>
                <p class="card-text">{{ $producto->descripcion }}</p>
                <p><strong>Precio: </strong>${{ $producto->precio }}</p>
            </div>
        </div>
    </div>
@endsection
