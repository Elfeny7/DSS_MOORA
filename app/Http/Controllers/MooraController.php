<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Services\MooraService;
use Illuminate\Http\Request;

class MooraController extends Controller
{
    protected MooraService $service;

    public function __construct(MooraService $service)
    {
        $this->service = $service;
    }

    public function input()
    {
        $totalAlternative = Alternative::count();
        $totalCriteria = Criteria::count();
        return view('input', compact('totalAlternative', 'totalCriteria'));
    }

    public function table(Request $request)
    {
        $x = $request->input('x');
        $y = $request->input('y');
        if (Alternative::count() <= $x && Criteria::count() <= $y) {
            $matrix = $this->service->getMatrix();
            $criteria = Criteria::all();
            $alternative = Alternative::all('name');
        } else {
            $matrix = null;
            $criteria = null;
            $alternative = null;
        }

        return view('table')->with(compact('x', 'y', 'matrix', 'criteria', 'alternative'));
    }

    public function calculate(Request $request)
    {
        $weight = $request->input('weight', []);
        $value = $request->input('value', []);
        $labelCriteria = $request->input('label_criteria', []);
        $labelAlternative = $request->input('label_alternative', []);

        $y = $request->input('y');
        $criteria = $request->input('criteria', []);

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

    public function save(Request $request)
    {
        $weight = $request->input('weight', []);
        $value = $request->input('value', []);
        $labelCriteria = $request->input('label_criteria', []);
        $labelAlternative = $request->input('label_alternative', []);
        $criteria = $request->input('criteria', []);
        $y = $request->input('y');
        $criteria = $this->service->fillCriteriaType($y, $criteria);

        $this->service->addMatrix($labelCriteria, $labelAlternative, $criteria, $weight, $value);
        return back();
    }
}
