<?php

namespace App\Models\Traits;

trait HasSearch
{
    public function scopeSearch($query, $data)
    {
        try {
            if ((auth()->user())) {
                $dataSearch = $query->where('persona_solicitante', 'like', '%' . $data . '%');
            }
            return $dataSearch;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function scopeSearchName($query, $data)
    {
        try {
            if ((auth()->user())) {
                $dataSearch = $query->where('name', 'like', '%' . $data . '%');
            } else {
                $dataSearch = $query->where('name', 'like', '%' . $data . '%')->where('status', 'A');
            }
            return $dataSearch;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function scopeSearchDate($query, $data)
    { {
            try {
                //buscar por rango de fechas
                if ((auth()->user())) {
                    // throw new \Exception($data, 401);
                    //"2022-09-29,2022-10-08"
                    $from = explode(',', $data)[0];
                    $to = explode(',', $data)[1];
                    $dataSearch = $query->whereBetween('fecha_ingreso', [$from, $to]);
                }
                return $dataSearch;
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }
}