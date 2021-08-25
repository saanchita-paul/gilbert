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
            $validTokenData = $svcUserInvitation->getInvitationByToken($request->toArray());
            $isValid = false;
            if(!is_null($validTokenData)){
                $isValid = true;
                return UserInvitationResource::make($validTokenData)->additional(['success'=> $isValid]);
            }

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

    public function passwordChange(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {

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
