<?php

namespace App\Http\Controllers;

use App\Support\CampaignProgress;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    public function complete(int $order): JsonResponse
    {
        CampaignProgress::markCompleted($order);

        return response()->json(['ok' => true, 'completed' => CampaignProgress::completedLevels()]);
    }
}
