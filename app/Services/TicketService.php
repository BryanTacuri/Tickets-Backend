<?php

namespace App\Services;

use App\Models\HistorialDetalle;
use App\Models\HistorialIncidencia;
use App\Models\Ticket;
use App\Services\Traits\HasRelations;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TicketService
{
    use HasRelations;

    public function getAll($data)
    {
        try {
            $query = $this->getDataQuery($data);
            $ticket['data'] = $query->get()->toArray();
            if (sizeof($ticket['data']) == 0) {
                $ticket['message'] = 'No hay tickets';
            } else {
                $ticket['message'] = 'Tickets encontrados';
            }
            return $ticket;
            // return $ola = Ticket::with('historialIncidencia')->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function paginate($data)
    {
        try {
            if ($data->per_page <= 0) {
                throw new \Exception('El parámetro per_page debe ser mayor a 0', 400);
            }
            $query = $this->getDataQuery($data);
            $ticket = $query->paginate($data->per_page)->toArray();
            if (sizeof($ticket['data']) == 0) {
                $ticket['message'] = 'No hay ticket';
            } else {
                $ticket['message'] = 'Tickets encontrados';
            }
            return $ticket;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getDataQuery($data)
    {
        if ((auth()->user())) {
            if (isset($_GET['status'])) {
                $query = new Ticket();
                $porciones = array_map('trim', explode(',', $_GET['status']));
                foreach ($porciones as $status) {
                    $query =  $query->orWhere("status", $status);
                }
            } else {
                $query = Ticket::where('status', '!=', 'E');
            }
        }
        if (isset($_GET) && count($_GET) > 0) {
            $query = $this->parametersGet($query, $data);
        }
        return $query;
    }

    private function parametersGet($query, $data)
    {
        $ticket = new Ticket();
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

    public function getId($data, $id)
    {
        try {
            $ticket = new Ticket();
            if ((auth()->user())) {
                $query = $ticket->where('id', $id)->where('status', '!=', 'E');
            }
            if (isset($data->fields)) {
                $query = $ticket->fields($query, $data->fields);
            }
            if (isset($_GET['embed'])) {
                $query = $ticket->embed($query, $_GET['embed']);
            }
            return $query->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {


            $validator = Validator::make($data->all(), [
                'persona_solicitante' => 'required|string',
                'descripcion' => 'required',
                'asunto' => 'required',
                'fecha_ingreso' => 'required',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error, 400);
            }

            $ticket = new Ticket();
            $ticket->persona_solicitante
                = $data->persona_solicitante;
            $ticket->fecha_ingreso
                = $data->fecha_ingreso;
            $ticket->asunto = $data->asunto;
            $ticket->descripcion = $data->descripcion;
            $ticket->save();

            if (isset($data->historial)) {
                $this->historial = new HistorialService();
                $this->historial->create($data->historial, $ticket->id);
            }

            DB::commit();
            return  $ticket;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data->all(), [
                'persona_solicitante' => 'required',
                'fecha_ingreso' => 'required',
                'asunto' => 'required',
                'descripcion' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }
            $ticket = Ticket::find($id);
            if ($ticket == null) {
                throw new \Exception('No existe este ticket', 404);
            }
            $ticket->persona_solicitante
                = $data->persona_solicitante;
            $ticket->fecha_ingreso
                = $data->fecha_ingreso;
            $ticket->asunto = $data->asunto;
            $ticket->descripcion = $data->descripcion;
            $ticket->status = $data->status;

            //actualizar el historial
            if (isset($data->historial)) {
                $this->historial = new HistorialService();
                if ($data->historial['id']) {

                    $this->historial->update($data->historial, $data->historial['id']);
                } else {

                    $this->historial->create($data->historial, $ticket->id);
                }
            }



            $ticket->update();

            DB::commit();

            return $ticket;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $ticket = Ticket::find($id);
            if ($ticket == null) {
                throw new \Exception('No existe este ticket', 404);
            }

            //recuperar los historiales
            $historial = HistorialIncidencia::where('tickets_id', $id)->get();


            foreach ($historial as $key => $value) {
                //eliminar de la BD
                $value->delete();
                //recuperar los detalles
                $detalle = HistorialDetalle::where('historial_incidencias_id', $value->id)->get();
                foreach ($detalle as $key => $value) {
                    $value->delete();
                }
            }

            $ticket->delete();
            return $ticket;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function activar($id)
    {
        DB::beginTransaction();
        try {
            $query = Ticket::find($id);
            if ($query == null) {
                throw new \Exception('No existe este ticket', 404);
            }

            $query->status = 'P';
            $query->update();
            DB::commit();

            return $query;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}