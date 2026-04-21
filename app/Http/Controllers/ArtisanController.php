<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ArtisanController extends Controller
{
    /**
     * Run an artisan command via web request.
     * 
     * Usage: /admin/artisan/{command}?secret=YOUR_SECRET
     */
    public function run(Request $request, string $command)
    {
        $secret = config('app.artisan_secret');

        if (!$secret || $request->query('secret') !== $secret) {
            abort(403, 'Unauthorized.');
        }

        // Whitelist safe commands
        $whiteList = [
            'migrate',
            'db:seed',
            'cache:clear',
            'config:clear',
            'view:clear',
            'route:clear',
            'optimize',
            'storage:link',
        ];

        // Basic validation: only allow whitelisted commands or those starting with them
        $isAllowed = false;
        foreach ($whiteList as $allowed) {
            if ($command === $allowed || str_starts_with($command, $allowed . ' ')) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Command not in whitelist.',
            ], 400);
        }

        try {
            Log::info("Running artisan command via web: php artisan {$command}");
            
            Artisan::call($command);
            $output = Artisan::output();

            return response()->json([
                'status' => 'success',
                'command' => "php artisan {$command}",
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error("Error running artisan command {$command}: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
