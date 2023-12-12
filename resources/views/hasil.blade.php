@extends('layouts.main')
@section('title', 'Hasil Perhitungan')
@section('content')
<div class="container">
    <h2>Hasil Normalisasi</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Alternatif</th>
                @foreach (range(1, count($normalizations[0])) as $i)
                <th>Kriteria {{ $i }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($normalizations as $i => $row)
            <tr>
                <td>Alternatif {{ $i + 1 }}</td>
                @foreach ($row as $j => $value)
                <td>{{ number_format($value, 4) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container">
    <h2>Optimasi Normalisasi</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Alternatif</th>
                @foreach (range(1, count($optimasi[0])) as $i)
                <th>Kriteria {{ $i }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($optimasi as $i => $row)
            <tr>
                <td>Alternatif {{ $i + 1 }}</td>
                @foreach ($row as $j => $value)
                <td>{{ number_format($value, 4) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="container">
    <h2>Perhitungan Max dan Min</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Alternatif</th>
                <th>Max</th>
                <th>Min</th>
                <th>Yi (Max-Min)</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($minMax as $i => $data)
            <tr>
                <td>Alternatif {{ $i + 1 }}</td>
                <td>{{ number_format($data['max'], 4) }}</td>
                <td>{{ number_format($data['min'], 4) }}</td>
                <td>{{ number_format($data['minMax'], 4) }}</td>
                <td>{{ $data['ranking'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection