<?php

namespace App\Services;

use App\Models\HistorialDetalle;
use App\Models\HistorialIncidencia;
use App\Services\Traits\HasRelations;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Services\StepService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Symfony\Component\VarDumper\Dumper\esc;

class HistorialService
{
    use HasRelations;

    public function getAll($data)
    {
        try {

            $query = $this->getDataQuery($data);
            $historiales['data'] = $query->toArray();
            if (sizeof($historiales['data']) == 0) {
                $historiales['message'] = 'No hay historiales';
            } else {
                $historiales['message'] = 'Historiales encontrados';
            }
            return $historiales;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getDataQuery($data)
    {
        if ((auth()->user())) {

            $query = HistorialIncidencia::all();
        }
        if (isset($_GET) && count($_GET) > 0) {
            $query = $this->parametersGet($query, $data);
        }


        return $query;
    }

    private function parametersGet($query, $data)
    {
        $ticket = new HistorialIncidencia();
        $ticketQuery = $query;

        if (isset($_GET['fields'])) {
            $ticketQuery = $ticket->fields($query, $_GET['fields']);
        }

        if (isset($_GET['embed'])) {
            $ticketQuery = $ticket->embed($query, $_GET['embed']);
        }

        if (isset($_GET['search'])) {
            $ticketQuery = $ticket->search($query, $_GET['search']);
        }
        if (isset($_GET['searchDate'])) {
            $ticketQuery = $ticket->searchDate($query, $_GET['searchDate']);
        }

        if (isset($_GET['sort'])) {
            $ticketQuery = $ticket->sort($query, $_GET['sort']);
        }

        if (isset($_GET['searchAll'])) {
            $ticketQuery = $ticket->searchAll($query, $_GET['searchAll'], 'ticket');
        }

        unset(
            $_GET['fields'],
            $_GET['embed'],
            $_GET['sort'],
            $_GET['search'],
            $_GET['per_page'],
            $_GET['page'],
            $_GET['status'],
            $_GET['searchDate'],
            $_GET['searchAll']
        );

        if (isset($_GET) && count($_GET) > 0) {
            $ticketQuery = $ticket->parameters($query, $_GET);
        }

        return $ticketQuery;
    }




    public function create($data, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data, [
                'comentario' => 'string',
                'usuario_soporte' => 'required|string',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }






            $historial = new HistorialIncidencia();


            $historial->comentario = $data['comentario'];
            $historial->fecha_atencion = Carbon::now();
            $historial->usuario_soporte = $data['usuario_soporte'];
            $historial->tickets_id = $id;
            $historial->save();


            if (isset($data['details'])) {
                $this->serviceDetalle = new HistorialDetalleService();

                //recorrer los detalles
                foreach ($data['details'] as $detail) {
                    $detail['historial_id'] = $historial->id;
                    $this->serviceDetalle->create($detail);
                }
            }


            DB::commit();
            return $historial;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }



    public function update($data, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($data, [
                'usuario_soporte' => 'required|string',
                'comentario' => 'required|string',

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }


            $historial = HistorialIncidencia::find($id);

            if ($historial == null) {
                throw new \Exception('No existe este Historial', 404);
            }


            $historial->usuario_soporte = $data['usuario_soporte'];
            $historial->comentario = $data['comentario'];



            if (isset($data['details'])) {
                $this->historialDetalleService = new HistorialDetalleService();

                foreach ($data['details'] as $detail) {
                    if ($detail['id']  == null) {
                        $detail['historial_id'] = $historial->id;
                        $this->historialDetalleService->create($detail);
                    } else {
                        if ($detail['status'] == 'E') {
                            $this->historialDetalleService->delete($detail['id']);
                        } else {

                            $this->historialDetalleService->update($detail, $detail['id']);
                        }
                    }
                }
            }

            $historial->update();


            DB::commit();
            return $historial;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }



    public function delete($id)
    {
        try {
            $historial = HistorialIncidencia::find($id);
            if ($historial == null) {
                throw new \Exception('No existe esta subsección', 404);
            }
            $historial->status = 'I';

            $historial->date_delete = Carbon::now();
            $historial->update();
            return $historial;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function activar($id)
    {
        DB::beginTransaction();
        try {


            $query = HistorialDetalle::find($id);
            if ($query == null) {
                throw new \Exception('No existe este manual', 404);
            }

            $query->status = 'A';
            $query->update();
            DB::commit();

            return $query;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}