<?php

namespace App\Services;

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
}
