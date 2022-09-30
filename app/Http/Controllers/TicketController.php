<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Utils\Pagination;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new TicketService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $pagination = null;
            if (isset($_GET['per_page'])) {
                $response = $this->service->paginate($request);
                $pagination = new Pagination($response);
            } else {
                $response = $this->service->getAll($request);
            }
            $tickets = $response['data'];
            $message = $response['message'];
            $this->setPagination($pagination);
            $this->setDataCorrect($tickets, $message, 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request)
    {
        try {
            $ticket = $this->service->create($request);
            $this->setDataCorrect($ticket, 'Ticket creado correctamente', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function getById(Request $request, $id)
    {
        try {
            $ticket = $this->service->getId($request, $id);
            $this->setDataCorrect($ticket, 'Ticket encontrado', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $ticket = $this->service->update($request, $id);
            $this->setDataCorrect($ticket, 'Ticket actualizado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function activar($id)
    {
        try {

            $ticket = $this->service->activar($id);
            $this->setDataCorrect($ticket, 'Ticket  activado', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }




    public function delete($id)
    {
        try {
            $ticket = $this->service->delete($id);
            $this->setDataCorrect($ticket, 'Ticket eliminado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }
}