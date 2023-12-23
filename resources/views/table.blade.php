@extends('layouts.main')
@section('title', 'Tabel')
@section('content')
    <div class="container p-5 mt-5 border border-dark rounded-5">
        <h2>Form Tabel dengan {{ $x }} Alternatif dan {{ $y }} Kriteria</h2>
        <form id="moora-form" method="POST" action="{{ route('result') }}">
            @csrf
            <input type="hidden" name="y" value="{{ $y }}">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        @for ($i = 0; $i < $y; $i++)
                            <th>
                                <div class="input-group">
                                    <div class="input-group-text d-flex align-items-center">
                                        <span class="me-2">Cost</span>
                                        <input class="form-check-input mt-0" name="criteria[{{ $i }}]"
                                            type="checkbox"
                                            {{ isset($criteria[$i]) && $criteria[$i]->is_cost ? 'checked' : '' }}
                                            aria-label="Checkbox for following text input">
                                    </div>
                                    <input type="text" class="form-control" name="label_criteria[{{ $i }}]"
                                        aria-label="Text input with checkbox"
                                        value="{{ $criteria[$i]->name ?? 'Kriteria ' . $i + 1 }}">
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
                                <input type="number" name="weight[]" value="{{ $criteria[$i]->weight ?? '' }}"
                                    class="form-control" step="any" required>
                            </td>
                        @endfor
                    </tr>
                    @for ($row = 0; $row < $x; $row++)
                        <tr>
                            <th>
                                <input type="text" class="form-control" name="label_alternative[{{ $row }}]"
                                    value="{{ $alternative[$row]->name ?? 'Alternatif ' . $row + 1 }}" />
                            </th>
                            @for ($col = 0; $col < $y; $col++)
                                <td>
                                    <input type="number" name="value[{{ $row }}][{{ $col }}]"
                                        value="{{ $matrix[$row][$col] ?? '' }}" class="form-control" step="any"
                                        required>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
            <button type="submit" id="calculate-btn" class="btn btn-primary">Hitung</button>
            <button type="submit" id="save-btn" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection

@push('script')
    <script>
        $('#calculate-btn').click(function(e) {
            e.preventDefault();
            $('#moora-form').attr('action', "{{ route('result') }}");
            $('#moora-form').submit();
        });

        $('#save-btn').click(function(e) {
            e.preventDefault();
            $('#moora-form').attr('action', "{{ route('save') }}").submit();
            $('#moora-form').submit();
        });
    </script>
@endpush
