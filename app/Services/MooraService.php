<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use Exception;
use Illuminate\Support\Facades\DB;

class MooraService
{
    public function fillCriteriaType($totalCriteria, $criteria)
    {
        for ($i = 0; $i < $totalCriteria; $i++) {
            if (!isset($criteria[$i])) {
                $criteria[$i] = 'off';
            }
        }

        return $criteria;
    }

    public function normalization($value)
    {
        $normalized = $value;
        $sum = array_fill(0, count($value[0]), 0);
        for ($i = 0; $i < count($value); $i++) {
            for ($j = 0; $j < count($value[0]); $j++) {
                $sum[$j] += (pow($value[$i][$j], 2));
            }
        }
        for ($i = 0; $i < count($value); $i++) {
            for ($j = 0; $j < count($value[0]); $j++) {
                $normalized[$i][$j] = number_format($value[$i][$j] / sqrt($sum[$j]), 4);
            }
        }
        return $normalized;
    }

    public function optimization($normalized, $weight)
    {
        $optimized = $normalized;
        for ($i = 0; $i < count($normalized); $i++) {
            for ($j = 0; $j < count($normalized[0]); $j++) {
                $optimized[$i][$j] *= $weight[$j];
            }
        }
        return $optimized;
    }

    public function minMax($optimized, $criteria)
    {
        for ($i = 0; $i < count($optimized); $i++) {
            $max[$i] = 0;
            $min[$i] = 0;
            for ($j = 0; $j < count($optimized[0]); $j++) {
                if ($criteria[$j] == 'on') {
                    $min[$i] += $optimized[$i][$j];
                } else {
                    $max[$i] += $optimized[$i][$j];
                }
            }
            $minMax[$i] = [
                'max' => $max[$i],
                'min' => $min[$i],
                'minMax' => $max[$i] - $min[$i],
            ];
        }

        $ranking = $this->ranking($minMax);

        foreach ($minMax as $key => &$data) {
            $data['ranking'] = $ranking[$key];
        }

        return $minMax;
    }

    public function ranking($minMax)
    {
        $values = array_column($minMax, 'minMax');

        $sortedValues = $values;
        rsort($sortedValues);

        $ranking = array_map(function ($value) use ($sortedValues) {
            return array_search($value, $sortedValues) + 1;
        }, $values);

        return $ranking;
    }

    public function addMatrix($labelCriteria = [], $labelAlternative = [], $criteria = [], $weight = [], $value = [])
    {
        try {
            for ($i = 0; $i < count($criteria); $i++) {
                $criteriaObj[] = [
                    'id' => $i + 1,
                    'name' => $labelCriteria[$i],
                    'weight' => $weight[$i],
                    'is_cost' => isset($criteria[$i]) && $criteria[$i] == 'on',
                ];
            }

            for ($i = 0; $i < count($labelAlternative); $i++) {
                $alternativeObj[] = [
                    'id' => $i + 1,
                    'name' => $labelAlternative[$i],
                ];
                for ($j = 0; $j < count($labelCriteria); $j++) {
                    $valueObj[] = [
                        'alternative_id' => $i + 1,
                        'criteria_id' => $j + 1,
                        'value' => $value[$i][$j],
                    ];
                }
            }
            DB::beginTransaction();

            Criteria::upsert($criteriaObj, ['id']);
            Alternative::upsert($alternativeObj, ['id']);
            DB::table('alternative_criteria')->upsert($valueObj, ['alternative_id', 'criteria_id']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function getMatrix()
    {
        $data = DB::table('alternative_criteria')
            ->select('*')
            ->orderBy('alternative_id')
            ->orderBy('criteria_id')
            ->get();

        $matrix = [];

        foreach ($data as $d) {
            $matrix[$d->alternative_id - 1][$d->criteria_id - 1] = $d->value;
        }

        return $matrix;
    }
}
