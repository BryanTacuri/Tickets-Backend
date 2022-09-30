<?php
namespace App\Utils;

class Pagination
{
    public $current_page;
    public $first_page_url;
    public $from;
    public $last_page;
    public $last_page_url;
    public $next_page_url;
    public $path;
    public $per_page;
    public $prev_page_url;
    public $to;
    public $total;

    public function __construct($response)
    {
        $this->current_page = $response['current_page'];
        $this->first_page_url = $response['first_page_url'];
        $this->from = $response['from'];
        $this->last_page = $response['last_page'];
        $this->last_page_url = $response['last_page_url'];
        $this->next_page_url = $response['next_page_url'];
        $this->path = $response['path'];
        $this->per_page = $response['per_page'];
        $this->prev_page_url = $response['prev_page_url'];
        $this->to = $response['to'];
        $this->total = $response['total'];
    }
}