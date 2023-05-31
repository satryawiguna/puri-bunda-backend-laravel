<?php

namespace App\Services;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Core\Types\HttpResponseType;
use App\Core\Types\LogLevelType;
use App\Exceptions\InvalidLoginAttempException;
use App\Helper\Common;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\UserLog\UserLogStoreRequest;
use App\Repositories\Contracts\IUserLogRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IUserService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserService extends BaseService implements IUserService
{
    private readonly IUserRepository $_userRepository;
    private readonly IUserLogRepository $_userLogRepository;

    public function __construct(IUserRepository $userRepository,
                                IUserLogRepository $userLogRepository)
    {
        $this->_userRepository = $userRepository;
        $this->_userLogRepository = $userLogRepository;
    }

    public function register(RegisterRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();
            ;
            $register = $this->_userRepository->register($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $register,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("User register was succeed");

        } catch (QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);

        } catch (Exception $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function login(LoginRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $identity = (filter_var($request->identity, FILTER_VALIDATE_EMAIL)) ? 'email' : 'username';

            if (!Auth::attempt([$identity => $request->identity, "password" => $request->password])) {
                throw new InvalidLoginAttempException('Invalid login attempt');
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $userLogStoreRequest = new UserLogStoreRequest();
            $userLogStoreRequest->merge([
                "user_id" => $user->id,
                "log_level" => LogLevelType::INFO,
                "context" => "Login",
                "ipv4" => $request->ip()
            ]);

            $userLogStoreRequest->validate($userLogStoreRequest->rules());

            $this->_userLogRepository->createUserLog($userLogStoreRequest);

            $this->setGenericObjectResponse($response,
                [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("User login was succeed");
        } catch (InvalidLoginAttempException $ex) {
            $this->setMessageResponse($response,
                "ERROR",
                HttpResponseType::UNAUTHORIZED,
                "Invalid login attempt");

            Log::error("Invalid login attempt on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (ValidationException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (\Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function logout(): BasicResponse
    {
        $response = new BasicResponse();

        try {
            Auth::user()->tokens()->delete();

            $this->setMessageResponse($response,
                'SUCCESS',
                HttpResponseType::SUCCESS,
                'Logout succeed');

            Log::info("User logout was succeed");
        } catch (\Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }
}
