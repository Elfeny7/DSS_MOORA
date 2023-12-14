@extends('layouts.main')
@section('title', 'Input')
@section('content')
<div class="container p-5 mt-5 border border-dark rounded-5">
    <form method="post" action="{{ route('table') }}">
        <h2 class="text-center">Tentukan jumlah Alternatif dan Kriteria</h2>
        @csrf

        <div class="form-group mb-3">
            <label for="x" class="form-label">Jumlah Alternatif:</label>
            <input type="number" name="x" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="y" class="form-label">Jumlah Kriteria:</label>
            <input type="number" name="y" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Generate Tabel</button>
    </form>
</div>
@endsection