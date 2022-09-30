<?php

namespace App\Http\Controllers;

use App\Services\HistorialService;
use App\Services\SubsectionService;
use App\Utils\Pagination;
use Illuminate\Http\Request;

class HistorialIncidenciaController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'getById']]);
        $this->service = new HistorialService();
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {

        
            $pagination = null;

            $response = $this->service->getAll($request);

            $historiales = $response['data'];
            $message = $response['message'];
            $this->setPagination($pagination);
            $this->setDataCorrect($historiales, $message, 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function store(Request $request, $id)
    {
        try {
            $historial = $this->service->create($request, $id);
            $this->setDataCorrect($historial, 'Historial creado correctamente', 201);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }

    public function update(Request $request, $id)
    {
        try {
            $historial = $this->service->update($request, $id);
            $this->setDataCorrect($historial, 'Historial actualizado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }


    public function activar($id)
    {
        try {

            $historial = $this->service->activar($id);
            $this->setDataCorrect($historial, 'Historial activado', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }


    public function delete($id)
    {
        try {
            $historial = $this->service->delete($id);
            $this->setDataCorrect($historial, 'Historial eliminado correctamente', 200);
        } catch (\Exception $e) {
            $this->setError($e->getMessage(), $e->getCode());
        }
        return $this->returnData();
    }
}