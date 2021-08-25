<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agency\PasswordChangeRequest;
use App\Http\Resources\UserInvitationResource;
use App\Services\UserInvitationService;
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
    public function index(Request $request)
    {
        try {
            $svcUserInvitation = new UserInvitationService();
            $user = $svcUserInvitation->getInvitationByToken($request->toArray());

            if($user == null)
            {
                return response()->json(['success' => false, 'data' => $user]);
            }
            return response()->json(['success' => true, 'data' => $user]);

        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Password change for invitation user.
     *
     * @param PasswordChangeRequest $request
     *
     */

    public function passwordChange(Request $request)
    {
        try {
            $service = new UserInvitationService();
            $service->getInvitationByToken($request->toArray());


            $this->validate($request, [
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ]);

            return response()->json(['success' => true, 'message' => "Password successfully updated."]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
