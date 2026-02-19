@extends('layouts.app')

@section('title', 'Página de Prueba')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Página de Prueba de Bootstrap</h1>
        
        <div class="alert alert-success" role="alert">
            ¡Bootstrap está funcionando correctamente!
        </div>
        
        <button class="btn btn-primary">Botón Primary</button>
        <button class="btn btn-success">Botón Success</button>
        <button class="btn btn-danger">Botón Danger</button>
        <button class="btn btn-warning">Botón Warning</button>
        
        <hr>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>juan@ejemplo.com</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>María García</td>
                    <td>maria@ejemplo.com</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection