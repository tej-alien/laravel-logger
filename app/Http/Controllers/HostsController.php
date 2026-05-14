<?php

namespace App\Http\Controllers;

use App\Facades\LogViewer;
use App\Http\Resources\LogViewerHostResource;

class HostsController
{
    public function index()
    {
        return LogViewerHostResource::collection(
            LogViewer::getHosts()
        );
    }
}
