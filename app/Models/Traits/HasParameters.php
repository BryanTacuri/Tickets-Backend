<?php

namespace App\Models\Traits;

trait HasParameters
{
    public function scopeParameters($query, $data)
    {
        try {
            if (!property_exists($this, 'allowedParameters')) {
                throw new \Exception('Por favor agrega la propiedad public $allowedParameters en ' . get_class($this), 500);
            }
            if (is_null($data)) {
                return;
            }
            foreach ($data as $key => $field) {
                if (!collect($this->allowedParameters)->contains($key)) {
                    throw new \Exception('Parámetro inválido por GET, ' . $key . ' no fue encontrado', 400);
                }
                $dataParameters = $query->where($key, 'like', '%' . $field . '%');
            }
            return $dataParameters;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}