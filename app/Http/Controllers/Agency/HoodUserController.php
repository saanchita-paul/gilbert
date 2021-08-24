<?php
namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\AgentProfileResource;
use App\Http\Resources\Agency\HoodProfileResource;
use App\Services\Agency\SearchAgentProfileService;
use App\Services\Agency\SearchHoodUser;
use App\Services\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoodUserController extends Controller
{
    public function getAssignee(Request $request)
    {
        try {
            $service = new SearchHoodUser(array_merge(
                $request->toArray(),
                ['roles' => [RolePermission::ROLE_HOOD_TEAM_LEAD, RolePermission::ROLE_HOOD_CUSTOMER_REP]]
            ));
            return HoodProfileResource::collection($service->get());

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
