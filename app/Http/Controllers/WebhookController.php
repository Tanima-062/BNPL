<?php

namespace App\Http\Controllers;

use App\Services\WebhookService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        private WebhookService $service
    ) {}

    public function handle(Request $request)
    {
        return $this->service->process($request->all());
    }
}