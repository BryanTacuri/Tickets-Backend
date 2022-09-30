<?php

namespace App\Services;

use App\Models\File;
use App\Models\HistorialDetalle;
use App\Models\Step;
use App\Models\Subsection;
use App\Models\Tag;
use App\Services\Traits\HasRelations;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HistorialDetalleService
{
    use HasRelations;

    public function getAll($data)
    {
        try {
            $query = $this->getDataQuery($data);
            $detalles['data'] = $query->get()->toArray();
            if (sizeof($detalles['data']) == 0) {
                $detalles['message'] = 'No hay detalles';
            } else {
                $detalles['message'] = 'detalles encontrados';
            }
            return $detalles;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    private function getDataQuery($data)
    {
        if ((auth()->user())) {
            if (isset($_GET['status'])) {
                $query = new HistorialDetalle();
                $porciones = array_map('trim', explode(',', $_GET['status']));
                foreach ($porciones as $status) {
                    $query =  $query->orWhere("status", $status);
                }
            } else {
                $query = HistorialDetalle::where('status', '!=', 'E');
            }
        }


        return $query;
    }



    public function create($data)
    {

        DB::beginTransaction();
        try {

            $validator = Validator::make($data, [
                'descripcion' => 'required|string',

            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }

            $step = new HistorialDetalle();

            $step->descripcion = $data['descripcion'];

            $step->historial_incidencias_id = $data['historial_id'];

            $step->save();


            DB::commit();
            return $step;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }



    public function update($data, $id)
    {
        try {

            $validator = Validator::make($data, [
                'descripcion' => 'required|string',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }


            $step = HistorialDetalle::find($id);
            if ($step == null) {
                throw new \Exception('No existe este detalle', 404);
            }

            $step->descripcion = $data['descripcion'];

            $step->status = $data['status'];



            $step->update();

            DB::commit();
            return $step;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {

            $historial = HistorialDetalle::find($id);
            if ($historial == null) {
                throw new \Exception('No existe este Historial', 404);
            }



            $historial->delete();
            return $historial;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}