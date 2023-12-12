<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MooraController extends Controller
{
    public function table(Request $request)
    {
        $x = $request->input('x');
        $y = $request->input('y');

        return view('table')->with(['x' => $x, 'y' => $y]);
    }

    public function hitung(Request $request)
    {
        // dd($request->all());
        $bobot = $request->input('bobot');
        $value = $request->input('value');
        $y = $request->input('y');
        $kriteria = $request->input('kriteria');
        for ($i = 0; $i < $y; $i++) {
            if (!isset($kriteria[$i])) {
                $kriteria[$i] = 'off';
            }
        }
        $normalisasi = $this->normalisasi($value);
        $optimasi = $this->optimasi($normalisasi, $bobot);
        $minMax = $this->minMax($optimasi, $kriteria);
        $sortedMinMax = $this->ranking($minMax);

        return view('hasil', [
            'values' => $value,
            'weights' => $bobot,
            'normalizations' => $normalisasi,
            'optimasi' => $optimasi,
            'minMax' => $minMax,
            'sortedMinMax' => $sortedMinMax,
        ]);
    }

    public function normalisasi($value)
    {
        $normalisasi = $value;
        $sum = array_fill(0, count($value[0]), 0);
        for ($i = 0; $i < count($value); $i++) {
            for ($j = 0; $j < count($value[0]); $j++) {
                $sum[$j] += (pow($value[$i][$j], 2));
            }
        }
        for ($i = 0; $i < count($value); $i++) {
            for ($j = 0; $j < count($value[0]); $j++) {
                $normalisasi[$i][$j] = number_format($value[$i][$j] / sqrt($sum[$j]), 4);
            }
        }
        return $normalisasi;
    }

    public function optimasi($normalisasi, $bobot)
    {
        $optimasi = $normalisasi;
        for ($i = 0; $i < count($normalisasi); $i++) {
            for ($j = 0; $j < count($normalisasi[0]); $j++) {
                $optimasi[$i][$j] *= $bobot[$j];
            }
        }
        return $optimasi;
    }

    public function minMax($optimasi, $kriteria)
    {
        for ($i = 0; $i < count($optimasi); $i++) {
            $max[$i] = 0;
            $min[$i] = 0;
            for ($j = 0; $j < count($optimasi[0]); $j++) {
                if ($kriteria[$j] == 'on') {
                    $min[$i] += $optimasi[$i][$j];
                } else {
                    $max[$i] += $optimasi[$i][$j];
                }
            }
        }
        for ($k = 0; $k < count($optimasi); $k++) {
            $minMax[$k] = $max[$k] - $min[$k];
        }

        return $minMax;
    }

    public function ranking($minMax){
        $sortedMinMax = $minMax;
        arsort($sortedMinMax);

        return $sortedMinMax;
    }
}
