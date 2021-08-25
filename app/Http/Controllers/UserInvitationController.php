<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserInvitationController extends Controller
{
    /**
     * Getting User Invitation validation
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {
            $data = $request->toArray();
            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Creating new Office under Agency.
     *
     * @param PasswordChangeRequest $request
     *
     * @return UserInvitationResource|JsonResponse
     */

    public function createAgencyOffice(PasswordChangeRequest $request, int $agencyId): UserInvitationResource | JsonResponse
    {

    }
}
