<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\RegisteredUser;
use App\Models\Temp;
use Illuminate\Http\Request;
use Nette\Utils\Random;

use Firebase\JWT\JWT;
use DateTimeImmutable;
use App\Helpers\PublicHelper;
use Exception;

class StudentController extends Controller
{
    function checkEmail(Request $request)
    {
        try {
            if (!($this->verifyJWT())) {
                return response()->json([
                    'status' => false,
                    'message' => 'JWT token not matched'
                ]);
            }

            $RegisteredUser = RegisteredUser::where('email', $request->email);
            if (!$RegisteredUser->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No Data Found'
                ]);
            }

            $data = $RegisteredUser->first();

            $otp = Random::generate(6, '0-9');

            $otp_entry = Otp::updateOrCreate(
                [
                    'user_id' => $data->id
                ],
                [
                    'user_id' => $data->id,
                    'otp' => $otp,
                    'email' => $data->email
                ]
            );

            // dd($otp_entry);

            return response()->json([
                'status' => false,
                'message' => 'otp sent',
                'data' => $otp_entry
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong',
                'data' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    function storeData(Request $request)
    {
        try {
            $is_new = $request->is_new;
            if ($is_new == '1') {
                $entry_created = Temp::updateOrCreate(
                    [
                        'email' => $request->email
                    ],
                    $request->except('otp', 'email', 'is_new')
                );
                $data = $entry_created;

                // dd($entry_created);

                $otp = Random::generate(6, '0-9');

                $otp_entry = Otp::updateOrCreate(
                    [
                        'user_id' => $data->id
                    ],
                    [
                        'user_id' => $data->id,
                        'otp' => $otp,
                        'email' => $data->email
                    ]
                );

                // dd($otp_entry);

                return response()->json([
                    'status' => false,
                    'message' => (($is_new == '1') ? 'new' : 'old'),
                    'data' => $otp_entry
                ]);
            } else {
                $entry_created = RegisteredUser::where([
                    'email' => $request->email
                ])->update($request->except('otp', 'email', 'is_new'));
                return response()->json([
                    'status' => false,
                    'message' => (($is_new == '1') ? 'new' : 'old'),
                    'data' => $entry_created
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong',
                'data' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    function checkOtp(Request $request)
    {
        try {
            $OtpData = Otp::where([
                'email' => $request->email,
                'otp' => $request->otp
            ]);
            if (!$OtpData->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Otp Not Verified'
                ]);
            }

            $data = $OtpData->first();

            $userData = RegisteredUser::where('email', $request->email);
            if ($userData->exists()) {
                $userData = $userData->first();
                // dd($userData->first());
            } else {
                $tempData = Temp::where('email', $request->email)->first();

                $newUserData = $tempData->toArray();

                unset($newUserData['id'], $newUserData['updated_at'], $newUserData['created_at']);

                $userData = RegisteredUser::create(
                    $newUserData
                );
                $tempData->delete();
                \App\Events\UserCreated::dispatch($userData);
            }

            return response()->json([
                'status' => true,
                'message' => 'Otp Verified',
                'data' => $userData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong',
                'data' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    public function getToken(Request $request)
    {
        try {
            $secretKey  = env('JWT_KEY');
            $tokenId    = base64_encode(random_bytes(16));
            $issuedAt   = new DateTimeImmutable();
            $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();
            $serverName = "your.server.name";
            $userID   = $request->user ?? 'dummy';

            // Create the token as an array
            $data = [
                'iat'  => $issuedAt->getTimestamp(),
                'jti'  => $tokenId,
                'iss'  => $serverName,
                'nbf'  => $issuedAt->getTimestamp(),
                'exp'  => $expire,
                'data' => [
                    'userID' => $userID,
                ]
            ];

            // Encode the array to a JWT string.
            $token = JWT::encode(
                $data,
                $secretKey,
                'HS512'
            );
            return response()->json([
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong',
                'data' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    function verifyJWT()
    {
        $jwt = new PublicHelper();
        $data = $jwt->GetAndDecodeJWT();

        if (!empty($data?->data?->userID ?? '')) {
            return true;
        } else {
            return false;
        }
    }
}
