<?php
declare(strict_types=1);

namespace Modules\AdminTool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ModuleManagerController extends BaseControllerApi
{
    public function listModules()
    {
        Artisan::call('module:list');
        $output = Artisan::output();
        $modules = explode("\n", trim($output));

        $parsedModules = [];
        foreach ($modules as $line) {
            if (preg_match('/\[\s*(Enabled|Disabled)\s*\]\s*([\w]+)\s*.*\[(\d+)\]/', $line, $matches)) {
                $parsedModules[] = [
                    'status' => trim($matches[1]),
                    'name' => trim($matches[2]),
                    'order' => (int) trim($matches[3])
                ];
            }
        }

        return $this->successResponse($parsedModules, 'List of modules');
    }

    public function runModuleCommand(Request $request)
    {
        $command = $request->input('command');
        $module = $request->input('module', null);

        $allowedCommands = [
            'enable' => 'module:enable',
            'disable' => 'module:disable',
            'migrate' => 'module:migrate',
            'seed' => 'module:seed',
        ];

        if (!isset($allowedCommands[$command])) {
            return $this->errorResponse('Command not allowed', ResponseAlias::HTTP_FORBIDDEN);
        }

        $cmd = $allowedCommands[$command];
        $params = $module ? [$module] : [];

        Artisan::call($cmd, ['module' => $params]);

        $output = Artisan::output();

        return $this->successResponse(explode("\n", trim($output)), "Executed: {$cmd} on {$module}");
    }

    public function createModule(Request $request)
    {
        $moduleName = $request->input('name');

        if (!$moduleName) {
            return $this->errorResponse('Module name is required');
        }

        Artisan::call('module:make', ['module' => 'Demo']);

        $output = Artisan::output();

        return $this->successResponse(explode("\n", trim($output)), "Module {$moduleName} created.");
    }
}
