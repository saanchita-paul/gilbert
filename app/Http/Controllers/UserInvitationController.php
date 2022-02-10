<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agency\PasswordChangeRequest;
use App\Http\Requests\ValidateUserTokenRequest;
use App\Services\UserInvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserInvitationController extends Controller
{
    /**
     * Getting User Invitation validation
     *
     * @param ValidateUserTokenRequest $request
     *
     */
    public function validateInvitation(Request $request)
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
            return $this->sendErrorResponse($exception);
        }
    }

    public function createInvitation(){

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

            $this->validate($request, [
                'password' => 'required|min:6',
                'token' => 'required',
            ]);

            $service = new UserInvitationService();
            $service->updatePassword($request->toArray());
            $service->updateToken($request->toArray());
            $service->setEmailVerificationTime($request->toArray());

            return response()->json(['success' => true, 'message' => "Password successfully updated."]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
