<?php

namespace App\Http\Controllers;

use App\Services\MooraService;
use Illuminate\Http\Request;

class MooraController extends Controller
{
    protected MooraService $service;

    public function __construct(MooraService $service)
    {
        $this->service = $service;
    }

    public function table(Request $request)
    {
        $x = $request->input('x');
        $y = $request->input('y');

        return view('table')->with(['x' => $x, 'y' => $y]);
    }

    public function calculate(Request $request)
    {
        $weight = $request->input('weight');
        $value = $request->input('value');
        $labelCriteria = $request->input('label_criteria');
        $labelAlternative = $request->input('label_alternative');

        $y = $request->input('y');
        $criteria = $request->input('criteria');
        
        $criteria = $this->service->fillCriteriaType($y, $criteria);
        $normalized = $this->service->normalization($value);
        $optimized = $this->service->optimization($normalized, $weight);
        $minMax = $this->service->minMax($optimized, $criteria);

        return view('hasil', [
            'values' => $value,
            'weights' => $weight,
            'normalizations' => $normalized,
            'optimization' => $optimized,
            'minMax' => $minMax,
            'labelCriteria' => $labelCriteria,
            'labelAlternative' => $labelAlternative,
        ]);
    }
}
