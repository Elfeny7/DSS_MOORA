@extends('layouts.main')
@section('title', 'Tabel')
@section('content')
    <div class="container">
        <h2>Form Tabel dengan {{ $x }} Alternatif dan {{ $y }} Kriteria</h2>
        <form method="post" action="{{ route('result') }}">
            @csrf
            <input type="hidden" name="y" value="{{ $y }}">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        @for ($i = 0; $i < $y; $i++)
                            <th>
                                <div class="input-group mb-3">
                                    <div class="input-group-text d-flex align-items-center">
                                        <span class="me-2">Cost</span>
                                        <input class="form-check-input mt-0" name="criteria[{{ $i }}]" type="checkbox"
                                            aria-label="Checkbox for following text input">
                                    </div>
                                    <input type="text" class="form-control" name="label_criteria[{{ $i }}]"
                                        aria-label="Text input with checkbox" value="Kriteria {{ $i + 1 }}">
                                </div>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Bobot</th>
                        @for ($i = 0; $i < $y; $i++)
                            <td>
                                <input type="number" name="weight[]" class="form-control" step="any" required>
                            </td>
                        @endfor
                    </tr>
                    @for ($row = 0; $row < $x; $row++)
                        <tr>
                            <th>
                                <input type="text" class="form-control" name="label_alternative[{{ $row }}]"
                                    value="Alternatif {{ $row + 1 }}" />
                            </th>
                            @for ($col = 0; $col < $y; $col++)
                                <td>
                                    <input type="number" name="value[{{ $row }}][{{ $col }}]"
                                        class="form-control" step="any" required>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">hitung</button>
        </form>
    </div>
@endsection
