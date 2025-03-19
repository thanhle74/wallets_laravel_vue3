<?php
declare(strict_types=1);

namespace Modules\AdminTool\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Modules\AdminTool\Services\AdminToolService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdminToolController extends BaseControllerApi
{
    protected AdminToolService $adminToolService;

    public function __construct(AdminToolService $adminToolService)
    {
        $this->adminToolService = $adminToolService;
    }

    public function runCommand(Request $request)
    {
        $response = $this->adminToolService->runCommand($request->input('command'));
        return isset($response['error'])
            ? $this->errorResponse($response['error'], ResponseAlias::HTTP_FORBIDDEN)
            : $this->successResponse($response['output'], $response['message']);
    }

    public function getFactories()
    {
        return $this->successResponse($this->adminToolService->getFactories(), 'List of available factories');
    }

    public function getSeeders()
    {
        return $this->successResponse($this->adminToolService->getSeeders(), 'List of available seeders');
    }

    public function runFactory(Request $request)
    {
        $response = $this->adminToolService->runFactory($request->input('factory'), (int) $request->input('count', 10));
        return isset($response['error'])
            ? $this->errorResponse($response['error'])
            : $this->successResponse([], $response['message']);
    }

    public function runSeeder(Request $request)
    {
        $response = $this->adminToolService->runSeeder($request->input('seeder'));
        return isset($response['error'])
            ? $this->errorResponse($response['error'])
            : $this->successResponse($response['output'], $response['message']);
    }
}
