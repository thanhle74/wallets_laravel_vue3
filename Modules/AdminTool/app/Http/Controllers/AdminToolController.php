<?php
declare(strict_types=1);

namespace Modules\AdminTool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdminToolController extends BaseControllerApi
{
    public function runCommand(Request $request)
    {
        $command = $request->input('command');

        $allowedCommands = [
            'cache:clear',
            'route:clear',
            'config:clear',
            'view:clear',
            'migrate:fresh --seed',
        ];

        if (!in_array($command, $allowedCommands)) {
            return $this->errorResponse('Command not allowed', ResponseAlias::HTTP_FORBIDDEN);
        }

        Artisan::call($command);
        $output = Artisan::output();
        $outputArray = explode("\n", trim($output));

        return $this->successResponse($outputArray, 'Executed: ' . $command);
    }
}
