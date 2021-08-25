<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agency\PasswordChangeRequest;
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
     * Password change for invitation user.
     *
     * @param PasswordChangeRequest $request
     *
     */

    public function passwordChange(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {

            $this->validate($request, [
                'new_password'     => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ]);

            return response()->json(['success' => true, 'message' => "Password successfully updated."]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
