<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\PersonalAccessToken;
use App\Models\Receiver;
use App\Models\Schedule;
use App\Models\Tipper;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Hash,
    Log,
    Mail,
    Validator
};



class AuthController extends Controller
{
    /**
     * Handle an incoming registration request within Api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     *
     * @throws string
     */


     /**
         * @OA\Post(
         * path="/api/mobile/register",
         * summary="User register",
         * description="Comments in schema",
         * operationId="userRegister",
         * tags={"Mobile"},
         * @OA\RequestBody(
         *    required=true,
         *    description="User credentials",
         *    @OA\JsonContent(
         *       required={"email", "password"},
         *       @OA\Property(property="first_name", type="string", format="first_name", example="Name", description="if model: Tipper or Receiver"),
         *       @OA\Property(property="last_name", type="string", format="last_name", example="Surname", description="if model: Tipper or Receiver"),
         *       @OA\Property(property="name", type="string", format="name", example="OrgName", description="if model: Organization"),
         *       @OA\Property(property="email", type="string", format="email", example="email@gratus.user"),
         *       @OA\Property(property="password", type="string", format="password", example="password12345"),
         *       @OA\Property(property="repeat_password", type="string", format="repeat_password", example="password12345"),
         *       @OA\Property(property="model", type="string", format="model", example="Receiver"),
         *       @OA\Property(property="photo", type="blob", format="photo", example="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"),

        *    ),
        * ),
        * @OA\Response(
        *    response=200,
        *    description="Success",
        *    @OA\JsonContent(
        *       @OA\Property(property="message", type="string", example="Successfully created")
        *        )
        *     )
        * )
     */

    public function register(Request $request)
    {
        if ( Str::contains($request->email, '+') )
            return response()->json(['email' => 'The character "+" is not allowed in email address.'], 200);

        $code = Str::random(16);
        if ( $request->model == 'Organization' )
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'verificate_code' => $code,
                'password' => ['required', 'min:8', 'string', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],
            ],
            [
                'email.email'    => 'Email should contain @ and .',
                'password.min'   => 'Password should contain at least 8 characters',
                'password.regex' => 'Password should contain at least 1 numeric, 1 capital letter and 1 small letter',
            ]
        );
        else
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'regex:/^[a-zA-Z]+$/u', 'string', 'min:3', 'max:255'],
                'last_name' => ['required', 'regex:/^[a-zA-Z]+$/u', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'verificate_code' => $code,
                'password' => ['required', 'min:8', 'string', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'
                              ],
            ],
            [
                'first_name.min' => 'First name should contain at least 3 characters',
                'last_name.min' => 'Last name should contain at least 3 characters',
                'first_name.regex' => 'First name should contain only letters',
                'last_name.regex' => 'Last name should contain only letters',
                'email.email'    => 'Email should contain @ and .',
                'password.min'   => 'Password should contain at least 8 characters',
                'password.regex'   => 'Password should contain at least 1 numeric, 1 capital letter and 1 small letter',
            ]
        );

        if ( $validator->fails() ) {
            $i = 1;
            foreach ( $validator->errors()->all() as $value ) {
                switch (true){
                    case stripos($value,'First name should contain only letters') !== false :
                        $key = 'first_name';
                        $keys[$key] = $value;
                        return response()->json($keys, 533);
                        break;
                    case stripos($value,'First name should contain at least 3 characters') !== false :
                        $key = 'first_name';
                        $keys[$key] = $value;
                        return response()->json($keys, 534);
                        break;
                    case stripos($value,'Last name should contain only letters') !== false :
                        $key = 'last_name';
                        $keys[$key] = $value;
                        return response()->json($keys, 535);
                        break;
                    case stripos($value,'Last name should contain at least 3 characters') !== false :
                        $key = 'last_name';
                        $keys[$key] = $value;
                        return response()->json($keys, 536);
                        break;
                    case stripos($value,'Email should contain @ and .') !== false:
                        $key = 'email';
                        $keys[$key] = $value;
                        return response()->json($keys, 537);
                        break;
                    case stripos($value,'Password should contain at least 8 characters') !== false:
                        $key = 'password';
                        $keys[$key] = $value;
                        return response()->json($keys, 538);
                       break;
                    case stripos($value,'Password should contain at least 1 numeric, 1 capital letter and 1 small letter') !== false:
                        $key = 'password';
                        $keys[$key] = $value;
                        return response()->json($keys, 539);
                       break;
                    default:
                        $key = $i;
                        break;
                 }
                $keys[$key] = $value;
                $i += 1;
            }
            return response()->json($keys, 406);
        }

      $class = 'App\Models\\';
      if ( empty($request->model) )
        $class .= 'Receiver';
      else
        $class .= $request->model;

        if ( $request->password != $request->repeat_password )
            return response()->json(['password' => 'The passwords should match.'], 539);

        $user = $class::where('email', $request->email)->first();

        if ($user) {
            return response()->json(['email' => 'The email has already been taken.'], 406);
        }

        $code = Str::random(16);

        if ( $request->model == 'Organization' )
            $user = $class::create([
                'name' => $request->name,
                'email' => $request->email,
                'verificate_code' => $code,
                'password' => Hash::make($request->password),
            ]);
        else
            $user = $class::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'verificate_code' => $code,
                'password' => Hash::make($request->password),
            ]);

        if ( !empty($request->org_id) ) {
            $class::where(['id' => $user->id])->update([
                'org_id' => $request->org_id
            ]);
        }

        if ( !empty($request->photo) ) {
            $class::where(['id' => $user->id])->update([
                'photo' => $request->photo
            ]);
        }

        if ( $request->model == 'Organization' )
            $data = [
                'name' => $request->name,
                'code' => $code
            ];
        else
            $data = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'code' => $code
            ];

        Mail::send('emails.register', $data, function($messages) use ($request) {
            $messages->to( $request['email'] );
            $messages->subject('Account Activation');
        });

        return response()->json(['message' => 'Successfully created'], 200);
    }

    public function verificateEmail(Request $request, $code)
    {
        $models = ['App\Models\Receiver', 'App\Models\Tipper', 'App\Models\User', 'App\Models\Organization'];

        foreach ( $models as $model ) {
            $user = $model::where('verificate_code', '=', $code)->first();
            if ( isset($user) )
                break;
        }

        if (empty($user))
            return abort(403, 'Code does\'nt exist');

        $model::where(['id' => $user->id])->update([
            'verificate_code' => null,
            'status' => 200,
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);

        $data = array('name' => $user->first_name . ' ' . $user->last_name);

        Mail::send('emails.welcome', $data, function($messages) use ($user) {
            $messages->to( $user->email );
            $messages->subject('Welcome to gratus');
        });

        return view('mailVerificated');
    }

    /**
     * @OA\Post(
     * path="/api/mobile/login",
     * summary="Login",
     * description="Comments in schema",
     * operationId="userLogin",
     * tags={"Mobile"},
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="email", example="email@gratus.user"),
     *       @OA\Property(property="password", type="string", format="password", example="password12345"),
     *       @OA\Property(property="model", type="string", format="model", example="Receiver", description="Receiver as default. Can be 'Tiper', 'Receiver', 'Organization' "),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="token", type="string", example="0d21973f68fa3c115281b9d385a3fe310e178589cd135ac9a0d89756f3f77c81"),
     *       @OA\Property(property="msg", type="string", example="Have a great shift!")
     *        )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],
        ],
        [
            'email.email'    => 'Email should contain @ and .',
            'password.min'   => 'Password should contain at least 8 characters',
            'password.regex' => 'Password should contain at least 1 numeric, 1 capital letter and 1 small letter',
        ]
        );

        if ( $validator->fails() ) {
            $i = 1;
            foreach ( $validator->errors()->all() as $value ) {
                switch (true){
                    case stripos($value,'Email should contain @ and .') !== false:
                        $key = 'email';
                        $keys[$key] = $value;
                        return response()->json($keys, 541);
                        break;
                    case stripos($value,'password') !== false:
                        $key = 'password';
                        $keys[$key] = $value;
                        return response()->json($keys, 538);
                        break;
                    default:
                        $key = $i;
                        break;
                 }
                $keys[$key] = $value;
                $i += 1;
            }
            return response()->json($keys, 406);
        }

        $class = 'App\Models\\';
        if ( empty($request->model) )
            $model = 'Receiver';
        else
            $model = $request->model;

        $class .= $model;

        $user = $class::where('email', $request->email)->first();

        if ( ! $user )
            return response()->json(['error' => 'Email is incorrect'], 540);

        if ( ! Hash::check($request->password, $user->password) )
            return response()->json(['error' => 'Password is incorrect'], 542);

        if ( !empty($user->verificate_code) )
            return response()->json(['verified' => 'Email is not verified'], 406);

        $token = hash('sha256', Str::random(60));
        $user_token = PersonalAccessToken::create([
            'tokenable_type' => $class,
            'tokenable_id' => $user->id,
            'name' => 'apilogin',
            'abilities' => '["*"]',
            'token' => $token,
        ]);

        if ( !empty($user) )
            Schedule::create([
                'model'     => $model,
                'user_id'   => $user->id,
                'punch_in'  => date("Y-m-d H:i:s"),
                'token'     => $token
            ]);

        return response()->json([
                                    'token' => $token,
                                    'msg' => 'Have a great shift!'
                                ], 200);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/isEmailConfirmed",
     * summary="Check email confirm",
     * description="Comments in schema",
     * operationId="isEmailConfirmed",
     * tags={"Mobile"},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="email", example="valeriy.env@gmail.com"),
     *       @OA\Property(property="model", type="string", format="model", example="Tipper", description="Receiver as default. Can be 'Tiper', 'Receiver', 'Organization' "),
     *    ),
     * ),
     *
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="integer", example=1),
     *        )
     *    )
     * )
     */

    public function isEmailConfirmed(Request $request)
    {
        $class = 'App\Models\\';
        if ( empty($request->model) )
            $model = 'Receiver';
        else
            $model = $request->model;

        $class .= $model;
        $user = $class::where('email', $request->email)->first();

        if (!$user )
            return response()->json(['credentials' => 'User not found.'], 406);

        $token = hash('sha256', Str::random(60));
        $user_token = PersonalAccessToken::create([
            'tokenable_type' => $class,
            'tokenable_id' => $user->id,
            'name' => 'apilogin',
            'abilities' => '["*"]',
            'token' => $token,
        ]);

        if ( !empty($user) )
            Schedule::create([
                'model'     => $model,
                'user_id'   => $user->id,
                'punch_in'  => date("Y-m-d H:i:s"),
                'token'     => $token
            ]);

        if (!empty($user->email_verified_at))
            return response()->json([
                'status' => 1,
                'token'  => $token
            ], 200);
        else
            return response()->json([
                'status' => 0
            ], 406);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/transaction/create",
     * summary="Create transaction",
     * description="Comments in schema",
     * operationId="createTransaction",
     * tags={"Mobile"},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="org_id", type="integer", format="org_id", example="59"),
     *       @OA\Property(property="tipper_id", type="integer", format="tipper_id", example="40"),
     *       @OA\Property(property="receiver_id", type="integer", format="receiver_id", example="2"),
     *       @OA\Property(property="amount", type="integer", format="amount", example="50"),
     *    ),
     * ),
     *
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="42"),
     *       @OA\Property(property="transaction_id", type="string", example="AA5291"),
     *       @OA\Property(property="org_id", type="integer", example="59"),
     *       @OA\Property(property="receiver_id", type="integer", example="2"),
     *       @OA\Property(property="tipper_id", type="integer", example="40"),
     *       @OA\Property(property="amount", type="integer", example="50"),
     *       @OA\Property(property="stars", type="integer", example="5"),
     *       @OA\Property(property="comment", type="string", example="My comment here"),
     *       @OA\Property(property="status", type="string", example="Approved"),
     *       @OA\Property(property="anon_transfer", type="boolean", example="1"),
     *    )
     * )
     *)
     */

    public function createTransaction(Request $request)
    {
        if ( empty($request->tipper_id) ) {
            $token = get_token();
            $tipper = token_auth( $token );
            if ( !empty($tipper->id) )
                $tipper_id = $tipper->id;
        } else {
            $tipper_id = $request->tipper_id;
        }

        $transaction_id = chr(rand(65,90)) . chr(rand(65,90)) . rand(1000,9999);
        $org_id = !empty($request->org_id) ? $request->org_id : null;
        $receiver_id = !empty($request->receiver_id) ? $request->receiver_id : null;
        $amount = $request->amount;
        $status = 200;
        $stars = !empty($request->stars) ? $request->stars : null;
        $comment = !empty($request->comment) ? $request->comment : null;
        $anon_transfer = !empty($request->anon_transfer) ? $request->anon_transfer : null;

        $transaction =
        Transactions::create([
            'transaction_id' => $transaction_id,
            'org_id'         => $org_id,
            'receiver_id'    => $receiver_id,
            'tipper_id'      => $tipper_id,
            'amount'         => $amount,
            'stars'          => $stars,
            'comment'        => $comment,
            'status'         => $status,
            'anon_transfer'  => $anon_transfer
        ]);

        return response()->json($transaction, 200);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/logout",
     * summary="Logout",
     * operationId="userLogout",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="msg", type="string", example="You have been successfully logged out")
     *        )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $token = get_token();
        $user = token_auth( $token );

        if ( empty($user->id) )
            return $user;

        if ( !empty($user) )
            Schedule::where(['token' => $token])->update([
                'punch_out'  => date("Y-m-d H:i:s"),
                'token'      => null
            ]);

        $token_id = PersonalAccessToken::where(['token' => $token])->first();
        $token_id = $token_id->id;
        PersonalAccessToken::destroy($token_id);

        return response()->json(['msg' => 'You have been successfully logged out'], 200);
    }


    public function forgotPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ( empty($request->model) )
            $group = 'Receiver';
        else
            $group = $request->model;

        if ( $validator->fails() ) {
            $i = 1;
            foreach ( $validator->errors()->all() as $value ) {
                switch (true){
                    case stripos($value,'email') !== false:
                       $key = 'email';
                       break;
                    default:
                        $key = $i;
                        break;
                 }
                $keys[] = array($key => $value);
                $i += 1;
            }
            return response()->json($keys, 406);
        }

        $class = 'App\Models\\'.$group;

        $user = $class::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['email' => 'The provided email is incorrect.'], 406);
        }

        // $code = Str::random(16);
        $code = rand(100000, 999999);

        $class::where(['id' => $user->id])->update([
            'forgot_pass' => $code,
        ]);

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        $data = [
            'email' => $validated['email'],
            'code'  => $code
        ];

        Mail::send('emails.api.forgotPass', $data, function($messages) use ($validated) {
            $messages->to( $validated['email'] );
            $messages->subject('Forgot password');
        });

        return response(null, 200);
    }

    public function forgotPassView($code, Request $request)
    {
        $groups = array('User', 'Organization', 'Receiver', 'Tipper');

        foreach ( $groups as $group ) {
            $class = 'App\Models\\'.$group;
            $user = $class::where('forgot_pass', $code)->first();

            if ( !empty($user) ) {
                $model = $class;
            }
            // if ( empty($user) )
            //     return abort(403, 'This code doesn\'t exist');

            if ( !empty($user) )
                break;

            }
            return view('forgotPass', compact('user', 'class'));
    }

    public function forgotPassApp($code, Request $request)
    {
        $groups = array('User', 'Organization', 'Receiver', 'Tipper');

        foreach ( $groups as $group ) {
            $class = 'App\Models\\'.$group;
            $user = $class::where('forgot_pass', $code)->first();

            if ( !empty($user) ) {
                $model = $class;
                $model::where(['id' => $user->id])->update([
                    'forgot_pass' => 1,
                ]);
                break;
            }

            }
            return view('forgotPassApp');
    }

    public function forgotPassConfirm(Request $request)
    {
      if ( $request->password != $request->repeat_password )
        return back()->withErrors(['error' => 'Password mismatch']);

        $request->model::where(['id' => $request->user_id])->update([
            'password' => Hash::make($request->password),
            'forgot_pass' => null,
        ]);

      return dd('Password successfully changed');
    }

    /**
     * @OA\Post(
     * path="/api/mobile/setFirebaseToken",
     * summary="Set firebase token to user",
     * description="",
     * operationId="setFirebaseToken",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="firebase_token", type="string", example="token"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="No body in answer",
     *     )
     * )
    */

    public function setFirebaseToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firebase_token' => ['required', 'string'],
        ]);

        if ( $validator->fails() ) {
            $i = 1;
            foreach ( $validator->errors()->all() as $value ) {
                switch (true){
                    case stripos($value,'firebase token') !== false:
                       $key = 'firebase_token';
                       break;
                    default:
                        $key = $i;
                        break;
                 }
                $keys[] = array($key => $value);
                $i += 1;
            }
            return response()->json($keys, 406);
        }

        $user = token_auth( get_token() );
        if ( empty($user->id) )
            return $user;

        $user->update([
            'firebase_token' => $request->firebase_token
        ]);

        return response(null, 200);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/forgotPassCheck",
     * summary="Check user forgot pass confirm",
     * description="Comments in schema",
     * operationId="forgotPassCheck",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="email", type="string", example="email@gratus.user"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="integer", example="1"),
     *        )
     * ),
     * @OA\Response(
     *    response=406,
     *    description="Status: 0 or This email was not found",
     * ),
     * )
    */

    public function forgotPassCheckCode(Request $request)
    {
        $class = 'App\Models\\';
      if ( empty($request->model) )
        $class .= 'Receiver';
      else
        $class .= $request->model;


      $user = $class::where('email', $request->email)->first();

      if ( empty($user) )
        return response()->json(['error' => 'This email was not found'], 406);

      if ( !empty($user) )
          if ( $user->forgot_pass == 1 )
            return response()->json(['status' => 1], 200);

      return response()->json(['status' => 0], 406);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/forgotPassConfirm",
     * summary="Password recovery confirm",
     * description="",
     * operationId="forgotPassConfirm",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="email", type="email", example="email@gratus.user"),
     *       @OA\Property(property="password", type="password", example="password"),
     *       @OA\Property(property="repeat_password", type="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Password successfully changed",
     * ),
     * @OA\Response(
     *    response=545,
     *    description="Password should contain at least 8 characters",
     * ),
     * * @OA\Response(
     *    response=546,
     *    description="Passwords don't match",
     * ),
     * )
     *
    */

    public function forgotPassConfirmAPI(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],
        ],
        [
            'password.min'   => 'Password should contain at least 8 characters',
            'password.regex' => 'Password should contain at least 1 numeric, 1 capital letter and 1 small letter',
        ]
        );

        if ( $validator->fails() ) {
            $i = 1;
            foreach ( $validator->errors()->all() as $value ) {
                switch (true){
                    case stripos($value,'Password should contain at least 8 characters') !== false:
                        $key = 'password';
                        $keys[$key] = $value;
                        return response()->json($keys, 545);
                        break;
                    case stripos($value,'Password should contain at least 1 numeric, 1 capital letter and 1 small letter') !== false:
                        $key = 'password';
                        $keys[$key] = $value;
                        return response()->json($keys, 546);
                        break;
                    default:
                        $key = $i;
                        break;
                 }
                $keys[$key] = $value;
                $i += 1;
            }
            return response()->json($keys, 406);
        }

        $class = 'App\Models\\';
        if ( empty($request->model) )
            $class .= 'Receiver';
        else
            $class .= $request->model;

      $user = $class::where('email', $request->email)->first();

      if ( empty($user) )
        return response()->json(['error' => 'This email was not found'], 406);
    //   if ( $request->code != $user->forgot_pass )
    //     return response()->json(['code' => 'The code does not match'], 406);
      if ( empty($request->password) )
        return response()->json(['password' => 'Password field is empty'], 406);
    if ( $request->password != $request->repeat_password )
        return response()->json(['password' => "Passwords don't match"], 546);

    if ( $user->forgot_pass != 1 )
        return response()->json(['error' => 'The user did not initiate a password reset'], 406);

        $class::where(['id' => $user->id])->update([
            'password' => Hash::make($request->password),
            'forgot_pass' => null,
        ]);

        return response()->json(['success' => 'Password successfully changed'], 200);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/updateUserInfo",
     * summary="Update User Info",
     * description="Comments in schema",
     * operationId="updateUserInfo",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="User credentials",
     *    @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="first_name", type="string", example="name", description="if model: Tipper or Receiver"),
     *       @OA\Property(property="last_name", type="string", example="Surname", description="if model: Tipper or Receiver"),
     *       @OA\Property(property="name", type="string", example="OrgName", description="if model: Organization"),
     *       @OA\Property(property="email", type="string", example="email@gratus.user"),
     *       @OA\Property(property="photo", type="blob", example="iVBORw0KGgoAAAANSUhEUgAAAGYAAABoCAYAAAAD1YUSAAAK1WlDQ1BJQ0MgUHJvZmlsZQAASImVlwdUU0kXx+e99JDQQq+hd6QIBJASegClV1EJSSChxJCCiB0RV0BRVESwLOgqRcHVFZC1IKJYWAQL2DfIoqKuiwUbKvuAj7D71fP9z5nM79zcuXPvnDfv3AcA2Y8pEGTCigBk8cXCyEAfanxCIhX3BMAAAipAC7gwWSIBPTw8FCCamf+u9/2IL6KbNpOx/vX//yplNkfEAgBKQjiFLWJlIdyOjCcsgVAMAKoWsRstEwsmuRthFSGSIMLSSU6b5neTnDLFaPyUT3SkL8I6AOBJTKYwDQCSOWKn5rDSkDikIITt+GweH+FchD1ZXCYb4VaErbOylk7ybwibI/4CAMgkhGkpf4mZ9rf4KbL4TGaajKfrmhLejycSZDKX/59H87+VlSmZ2cMUGSSuMCgSmeWR87uTsTRExvyUBWEzzGNP+U8xVxIUM8MskW/iDLOZfiGytZkLQmc4lRfAkMURM6JnmCPyj5ph4dJI2V6pQl/6DDOFs/tKMmJkdi6HIYufx42Om+EcXuyCGRZlRIXM+vjK7EJJpCx/Dj/QZ3bfAFntWaK/1MtjyNaKudFBstqZs/lz+PTZmKJ4WW5sjp//rE+MzF8g9pHtJcgMl/lzMgNldlFOlGytGHk4Z9eGy84wnRkcPsPAAbiAQJAJaMAJ2AEg5uSKJ4vwXSpYLuSlccVUOnLTOFQGn2VrTXWwc3AAYPLeTj8KbyOm7iOk1j1rEyPn5/E7cld6Z22JyMkcQeKqO87azDUAUN0HQKslSyLMmbahJ38wgAgUkDeCJtADRsAc2CA5OgN34A38QTAIA9EgASwGLMAFWUAIloGVYB0oBMVgK9gJKsF+cADUgqPgOGgBp8F5cAlcA73gNrgPpGAYvACj4D0YhyAIB5EhCqQJ6UMmkBXkANEgT8gfCoUioQQoGUqD+JAEWgmth4qhMqgSqobqoB+hU9B56ArUB92FBqER6A30GUbBJFgF1oVN4TkwDabDIXA0vAhOg7PhPLgA3gJXwDXwEbgZPg9fg2/DUvgFPIYCKDmUGsoAZYOioXxRYahEVCpKiFqNKkKVo2pQjag2VBfqJkqKeon6hMaiKWgq2gbtjg5Cx6BZ6Gz0anQJuhJdi25Gd6JvogfRo+hvGDJGB2OFccMwMPGYNMwyTCGmHHMIcxJzEXMbM4x5j8Vi1bBmWBdsEDYBm45dgS3B7sU2Yduxfdgh7BgOh9PEWeE8cGE4Jk6MK8Ttxh3BncPdwA3jPuLl8Pp4B3wAPhHPx+fjy/H1+LP4G/in+HGCIsGE4EYII7AJywmlhIOENsJ1wjBhnKhENCN6EKOJ6cR1xApiI/Ei8QHxrZycnKGcq1yEHE9urVyF3DG5y3KDcp9IyiRLki8piSQhbSEdJrWT7pLekslkU7I3OZEsJm8h15EvkB+RP8pT5G3lGfJs+TXyVfLN8jfkXykQFEwU6AqLFfIUyhVOKFxXeKlIUDRV9FVkKq5WrFI8pTigOKZEUbJXClPKUipRqle6ovRMGadsquyvzFYuUD6gfEF5iIKiGFF8KSzKespBykXKsApWxUyFoZKuUqxyVKVHZVRVWXWuaqxqrmqV6hlVqRpKzVSNoZapVqp2XK1f7bO6rjpdnaO+Sb1R/Yb6Bw1tDW8NjkaRRpPGbY3PmlRNf80MzW2aLZoPtdBalloRWsu09mld1HqpraLtrs3SLtI+rn1PB9ax1InUWaFzQKdbZ0xXTzdQV6C7W/eC7ks9NT1vvXS9HXpn9Ub0Kfqe+jz9Hfrn9J9TVal0aia1gtpJHTXQMQgykBhUG/QYjBuaGcYY5hs2GT40IhrRjFKNdhh1GI0a6xvPN15p3GB8z4RgQjPhmuwy6TL5YGpmGme60bTF9JmZhhnDLM+sweyBOdncyzzbvMb8lgXWgmaRYbHXotcStnSy5FpWWV63gq2crXhWe636rDHWrtZ86xrrARuSDd0mx6bBZtBWzTbUNt+2xfbVHOM5iXO2zema883OyS7T7qDdfXtl+2D7fPs2+zcOlg4shyqHW45kxwDHNY6tjq/nWs3lzN03944TxWm+00anDqevzi7OQudG5xEXY5dklz0uAzQVWjithHbZFePq47rG9bTrJzdnN7Hbcbc/3G3cM9zr3Z/NM5vHmXdw3pCHoQfTo9pD6kn1TPb83lPqZeDF9Krxeuxt5M32PuT9lG5BT6cfob/ysfMR+pz0+eDr5rvKt90P5RfoV+TX46/sH+Nf6f8owDAgLaAhYDTQKXBFYHsQJigkaFvQAEOXwWLUMUaDXYJXBXeGkEKiQipDHodahgpD2+bD84Pnb5//YIHJAv6CljAQxgjbHvYw3Cw8O/znCGxEeERVxJNI+8iVkV1RlKglUfVR76N9okuj78eYx0hiOmIVYpNi62I/xPnFlcVJ4+fEr4q/lqCVwEtoTcQlxiYeShxb6L9w58LhJKekwqT+RWaLchddWay1OHPxmSUKS5hLTiRjkuOS65O/MMOYNcyxFEbKnpRRli9rF+sF25u9gz3C8eCUcZ6meqSWpT5L80jbnjbC9eKWc1/yfHmVvNfpQen70z9khGUczpjIjMtsysJnJWed4ivzM/idS/WW5i7tE1gJCgXSbLfsndmjwhDhIREkWiRqFasgDVK3xFyyQTKY45lTlfNxWeyyE7lKufzc7uWWyzctf5oXkPfDCvQK1oqOlQYr160cXEVfVb0aWp2yumON0ZqCNcNrA9fWriOuy1j3S75dfln+u/Vx69sKdAvWFgxtCNzQUChfKCwc2Oi+cf936O943/Vscty0e9O3InbR1WK74vLiLyWskqub7TdXbJ7Ykrqlp9S5dN9W7Fb+1v5tXttqy5TK8sqGts/f3ryDuqNox7udS3ZeKZ9bvn8XcZdkl7QitKJ1t/Hurbu/VHIrb1f5VDXt0dmzac+Hvey9N/Z572vcr7u/eP/n73nf36kOrG6uMa0pP4A9kHPgycHYg10/0H6oO6R1qPjQ18P8w9LayNrOOpe6unqd+tIGuEHSMHIk6UjvUb+jrY02jdVNak3Fx8AxybHnPyb/2H885HjHCdqJxp9MftpzknKyqBlqXt482sJtkbYmtPadCj7V0ebedvJn258PnzY4XXVG9UzpWeLZgrMT5/LOjbUL2l+eTzs/1LGk4/6F+Au3OiM6ey6GXLx8KeDShS5617nLHpdPX3G7cuoq7WrLNedrzd1O3Sd/cfrlZI9zT/N1l+utva69bX3z+s7e8Lpx/qbfzUu3GLeu3V5wu68/pv/OQNKA9A77zrO7mXdf38u5N35/7QPMg6KHig/LH+k8qvnV4tcmqbP0zKDfYPfjqMf3h1hDL34T/fZluOAJ+Un5U/2ndc8cnp0eCRjpfb7w+fALwYvxl4W/K/2+55X5q5/+8P6jezR+dPi18PXEm5K3mm8Pv5v7rmMsfOzR+6z34x+KPmp+rP1E+9T1Oe7z0/FlX3BfKr5afG37FvLtwUTWxISAKWROtQIoZMCpqQC8OYz0xQkAUHoBIC6c7qunBE1/C0wR+E883XtPyRmAaqQPiyMCELIBgMpBAMwakbjNAISTAYh2BbCjo2z8Q6JUR4fpWCSk58M8mph4i/S/uO0AfN06MTFeMzHx9QCS7AMA2vnT/fyk9JBvi4VrAaFzM/g3mu71/1LjP89gMoOpHP42/wmQzhXL5EK5PgAAAIplWElmTU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAIdpAAQAAAABAAAATgAAAAAAAACQAAAAAQAAAJAAAAABAAOShgAHAAAAEgAAAHigAgAEAAAAAQAAAGagAwAEAAAAAQAAAGgAAAAAQVNDSUkAAABTY3JlZW5zaG90bUNrtAAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAAdZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDYuMC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iPgogICAgICAgICA8ZXhpZjpQaXhlbFlEaW1lbnNpb24+MTA0PC9leGlmOlBpeGVsWURpbWVuc2lvbj4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjEwMjwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlVzZXJDb21tZW50PlNjcmVlbnNob3Q8L2V4aWY6VXNlckNvbW1lbnQ+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgqh8GwdAAAAHGlET1QAAAACAAAAAAAAADQAAAAoAAAANAAAADQAAAQHrIPkYgAAA9NJREFUeAHsmtkrrVEYxp9tnjJLGRMyRIYMGSIylMKFv1JJpogrJa4kKUkulBvlwpDIfM55Vh0pNmvvby3fu2q9N3vvb1jfs57ffvd+1xD58y/gQ5wDEQ9GHBMlyIORyQUejAcj1AGhsnzGeDBCHRAqy2eMByPUAaGyfMZ4MEIdECrLZ4wHI9QBobJ8xngwQh0QKstnjAcj1AGhsnzGeDBCHRAqy2eMByPUAaGyxGTM09MTLi4ucHV1hdvbWzw8PODl5QWJiYlISkpCZmYmsrOzUVRUhNzcXEQiEaGWmpEVKpjX11ecnZ3h9PRUQdHdsJOWloby8nLU1NQoSGaskNVK6GC2trZwfn4etyslJSVobW1FTk5O3G1IvDFUMDSEWRMUDn/WGhoa0NzcjISEBIk+x6wpdDCm4LCdgoICDAwMgD91rocIMDTRROawnaysLAwNDalXfnY1xIChgSbhjI2NITU11VUu8jb8mYJTWFiI4eFhVW67SEdUxvw30BQcFgSs2FwMkWBopAk4rNbGx8eRl5fnHBtrYJ6fn5GcnBzIEBNwOFMwMjISSEcYN1sDs7Ozo0bljY2NgfplAs7o6Cj4n+NSWAHz+PiIhYUFvL29oampSQ38gpgSFA6nb/r7+4NI+PV7rYA5OTnB7u7ue2eYNS0tLe+f43kTBA4nQmdmZpyq0KyA2d7eVpOTHwHU19ejra3t46GY33MGenl5GXyNNQYHB8F5NVfCCpilpSXc3d198qCurg7t7e2fjuscCJIxbN/EF0NHp6lrjINhNTY3NxdVX21tLTo6OqKe/+pEUChss7S0VM2jfdW+xGPGwdzf32NxcfHbvlZXV6Ozs1NrscsEFIrhssDExMS3uiSdNA7m+voaa2trP/axqqoK3d3d38IxBYViMjIyMD09/aMuKRcYB3Nzc4PV1VWt/lVWVqKnp+dLOCahUAyXpqemprR0SbjIOBiOYebn57X7VlFRoeB8XOAyDYViuBwwOTmprSvsC42DYYdmZ2fV4FK3c2VlZejr61OrjzagUEdxcbGabdbVFPZ1VsCsr6/j8vIypr5xjNHb2wuOgYLsAYj20HiqwWht/cZxK2D29/dxdHQUs/6UlJS4Bo86D+rq6gKrQVfCChh+4zc3N8V4wOl/VmTp6eliNP0kxAoY7g/j6J9jGgmRn5+v1mUkaNHVYAUMH354eIiDgwNdHVavY8XH4oJFhithDQy3uK6srIBTNBLCNTjWwBDG8fEx9vb2JHBRGlyCYxUM/2tYOnOjuJRwBY5VMITBnfsbGxvWyuB4gLsA5y8AAAD//7rwuJUAAAJdSURBVO3avYrCQBAH8ImIiI0WFn49iKAg+AY2vq6NWFlpI4haWPmFIKgg4t1NuECQM+w5mZidnQUxxsxk/f+ICRrv62cA89hutzAYDODxeDDvybx9JpOBVqsFjUbDvCjBLb0kYPDzrNdrGI1GimOImxgMzmez2cBwOITb7WY4Pf7N0nrkJAqDMV8uF//I2e12/Kkb7iGNOInDBFktl0uYTCZwvV6DVR99ThvOx2BQAS8GVqsVLBYLOBwOxjAYYrFYhOPxaFxjsmGacD4KEw7rfD7756D9fg+n08k/ku73O+BFYy6Xg0KhAKVSCcrlMlQqFX/deDyG+XwebkNeTgtOamDeTVQqjvUwCCoRRwSMRBwxMNJwRMFIwhEHIwVHJIwEHLEwtuOIhrEZRzyMrThOwNiI4wyMbThOwdiE4xyMLThOwnDitNttqNfruAvScBYGU+P4VTqfz0Ov1yOhYLHTMBhA3Die50G/3wf8w40ynIeJG6dWq0Gn06GY+LUK8xthHEcO3ofQ7XYBv86oQ2FCCVJw4kTBKSlMCAYX38GJGwXnoTCYwtP4Dw4HCk5HYZ5QgpcmOFwoChMovHiOwuFEUZgXIOHVf+FwoyhMWCBieTqdwmw2A7wztFqtQrPZjOWSOGKXeo6JCif8Ht5njY9sNhtezbasJ3+2aGmNFYaWH1u1wrBFS2usMLT82KoVhi1aWmOFoeXHVq0wbNHSGisMLT+2aoVhi5bWWGFo+bFVKwxbtLTGCkPLj61aYdiipTVWGFp+bNUKwxYtrbHC0PJjq1YYtmhpjRWGlh9b9TdzvAAFOhzAIQAAAABJRU5ErkJggg=="),
     *       @OA\Property(property="password", type="string", example="password12345"),
     *       @OA\Property(property="org_id", type="integer", example="1")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="Data successfully changed"),
     *        )
     *     )
     * )
    */

    public function updateUserInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['regex:/^[a-zA-Z]+$/u', 'string', 'min:3', 'max:255'],
            'last_name' => ['regex:/^[a-zA-Z]+$/u', 'string', 'min:3', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'old_password' => ['string'],
            'password' => ['min:8', 'string', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],
          ],
          [
            'first_name.min' => 'First name should contain at least 3 characters',
            'last_name.min' => 'Last name should contain at least 3 characters',
            'first_name.regex' => 'First name should contain only letters',
            'last_name.regex' => 'Last name should contain only letters',
            'email.email'    => 'Email should contain @ and .',
            'password.min'   => 'Password should contain at least 8 characters',
            'password.regex' => 'Password should contain at least 1 numeric, 1 capital letter and 1 small letter'
          ]
        );

        if ( $validator->fails() ) {
            $i = 1;
            foreach ( $validator->errors()->all() as $value ) {
                switch (true){
                    case stripos($value,'email') !== false:
                    $key = 'email';
                    break;
                    case stripos($value,'First name') !== false:
                        $key = 'first_name';
                        break;
                    case stripos($value,'Last name') !== false:
                        $key = 'last_name';
                        break;
                    case stripos($value,'password') !== false:
                        $key = 'password';
                    break;
                    case stripos($value,'first name') !== false:
                        $key = 'first_name';
                    break;
                    case stripos($value,'last name') !== false:
                        $key = 'last_name';
                    break;
                    default:
                        $key = $i;
                        break;
                }
                $keys[$key] = $value;
                $i += 1;
            }
            return response()->json($keys, 406);
        }

        if ( $request->password != $request->repeat_password )
            return response()->json(['password' => 'The passwords should match.'], 406);

        $answer = json_decode($request->getContent());
        $user = token_auth( get_token() );
        if ( empty($user->id) )
            return $user;

        if ( !empty( $request->password ) )
            if ( !password_verify($request->old_password, $user->password))
                return response()->json(['old_password' => 'Old password is incorrect'], 406);

        if ( empty($answer) )
            return response()->json(['error' => 'Request is empty'], 406);

        if ( isset($answer->name) )
            $user->update(['name' => $answer->name]);

        if ( isset($answer->first_name) )
            $user->update(['first_name' => $answer->first_name]);

        if ( isset($answer->last_name) )
            $user->update(['last_name' => $answer->last_name]);

        if ( isset($answer->email) )
            $user->update(['email' => $answer->email]);

        if ( isset($answer->photo) )
            $user->update(['photo' => $answer->photo]);

        if ( isset($answer->password) )
            $user->update(['password' => Hash::make($answer->password)]);

        if ( isset($answer->org_id) )
            $user->update(['org_id' => $answer->org_id]);

        return response()->json(['success' => 'Data successfully changed'], 200);
    }

    /**
     * @OA\Get(
     * path="/api/mobile/getInfo",
     * summary="Get User Info",
     * description="Comments in schema",
     * operationId="getUserInfo",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="first_name", type="string", example="name", description="if model: Tipper or Receiver"),
     *       @OA\Property(property="last_name", type="string", example="Surname", description="if model: Tipper or Receiver"),
     *       @OA\Property(property="name", type="string", example="OrgName", description="if model: Organization"),
     *       @OA\Property(property="email", type="string", example="email@gratus.user"),
     *       @OA\Property(property="photo", type="blob", example="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"),
     *        )
     *     )
     * )
     */

    public function getInfo(Request $request)
    {
        $user_info = token_auth( get_token() );
        if ( empty($user_info->id) )
            return $user_info;

        $user['id'] = $user_info->id;

        if ( isset($user_info->name) )
            $user['name'] = $user_info->name;
        else {
            $user['first_name'] = $user_info->first_name;
            $user['last_name'] = $user_info->last_name;
        }

        $user['email'] = $user_info->email;
        $user['photo'] = $user_info->photo;

        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     * path="/api/mobile/userTransactions",
     * summary="Get User Info",
     * description="Comments in schema",
     * operationId="userTransactions",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="Choose model",
     *    @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="model", type="string", example="Tipper", description="if model: Tipper or Receiver"),
     *       @OA\Property(property="tips", type="string", example="total", description="can be: empty or 'total' or 'today'.")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Array of transactions. With this keys: 'transaction_id', 'tipper_id', 'amount', 'status'",
     * ),
     * @OA\Response(
     *    response=406,
     *    description="Unauthenticated.",
     * ),
     * )
     */

    public function getUserTransactions(Request $request)
    {
        $transaction_list = null;

        $user_info = token_auth( get_token() );
        if ( empty($user_info->id) )
            return $user_info;

        $user_id = $user_info->id;
        $i = 0;
        $tipper_id = null;

        if (empty($request->model) || $request->model == 'Receiver' ) {
            $transactions = Transactions::where('receiver_id', $user_id)->get();
            $today_transactions = Transactions::where('receiver_id', $user_id)->whereDate('created_at', Carbon::today())->get();
        }
        else {
            $transactions = Transactions::where('tipper_id', $user_id)->get();
            $today_transactions = Transactions::where('tipper_id', $user_id)->whereDate('created_at', Carbon::today())->get();
        }

        if ( empty($request->tips) ):
            foreach ( $transactions as $transaction ):
                $transaction_list[$i]['transaction_id'] = $transaction['transaction_id'];
                $receiver_id = $transaction['receiver_id'];
                if ( isset($receiver_id) ) {
                    $receiver = Receiver::where(['id' => $receiver_id])->first();
                    if ( isset($receiver) ) {
                        $transaction_list[$i]['receiver']['first_name'] = $receiver['first_name'];
                        $transaction_list[$i]['receiver']['last_name'] = $receiver['last_name'];
                        $transaction_list[$i]['receiver']['photo'] = $receiver['photo'];
                    }
                }
                $tipper_id = $transaction['tipper_id'];
                $tipper = Tipper::where(['id' => $tipper_id])->first();
                if ( isset($tipper) ) {
                  if ( empty($transaction['anon_transfer']) ) {
                    $transaction_list[$i]['tipper']['first_name'] = $tipper['first_name'];
                    $transaction_list[$i]['tipper']['last_name'] = $tipper['last_name'];
                    $transaction_list[$i]['tipper']['photo'] = $tipper['photo'];
                  }
                }
                $transaction_list[$i]['amount'] = $transaction['amount'];
                $transaction_list[$i]['status'] = $transaction['status'];
                $i += 1;
            endforeach;

            return response()->json($transaction_list, 200);
        elseif ( $request->tips == "today" ):
            $amount['tips'] = 0;
            foreach ( $today_transactions as $transaction ):
                $amount['tips'] += $transaction->amount;
            endforeach;
            return response()->json($amount, 200);

        elseif ( $request->tips == "total" ):
            $amount['tips'] = 0;
            foreach ( $transactions as $transaction ):
                $amount['tips'] += $transaction->amount;
            endforeach;
            return response()->json($amount, 200);
        endif;
    }


    /**
     * @OA\Post(
     * path="/api/mobile/getFeedbackList",
     * summary="Get Feedback List",
     * description="Comments in schema",
     * operationId="getFeedbackList",
     * tags={"Mobile"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="Choose model",
     *    @OA\JsonContent(
     *       required={},
     *       @OA\Property(property="receiver_id", type="integer", example=2, description="Choose receiver_id or set bearer token to header"),
     *       @OA\Property(property="date", type="string", example="", description="Can be: empty or 'today'.")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Feedback array.",
     * ),
     * @OA\Response(
     *    response=406,
     *    description="Unauthenticated.",
     * ),
     * )
     */

    public function getFeedbackList(Request $request)
    {
        $feedback_list = null;

        if ( empty($request->receiver_id) )
        {
            $token = get_token();
            $receiver = token_auth( $token );
            if ( !empty($receiver->id) )
                $receiver_id = $receiver->id;
            else
                return $receiver;
        }
        else
            $receiver_id = $request->receiver_id;

        $i = 0;

        if ($request->date == "today")
            $feedbacks = Transactions::where('receiver_id', $receiver_id)->whereDate('created_at', Carbon::today())->get();
        else
            $feedbacks = Transactions::where('receiver_id', $receiver_id)->get();


        foreach ( $feedbacks as $feedback ):
            $feedback_list[$i]['feedback'] = $feedback['comment'];
            $feedback_list[$i]['rating'] = $feedback['stars'];

            $tipper = Tipper::where([ 'id' => $feedback['tipper_id'] ])->first();
            if ( isset($tipper) ) {
                if ( empty($feedback['anon_transfer']) ) {
                    $feedback_list[$i]['tipper']['first_name'] = $tipper['first_name'];
                    $feedback_list[$i]['tipper']['last_name'] = $tipper['last_name'];
                    $feedback_list[$i]['tipper']['photo'] = $tipper['photo'];
                }
            }

            $i += 1;
        endforeach;

        return response()->json($feedback_list, 200);
    }

}

function get_token() {
    $token = null;

    $headers = apache_request_headers();
    if( isset($headers['Authorization']) ){
        if (strpos($headers['Authorization'], 'Bearer') !== false) {
            $token = str_replace('Bearer ', '',$headers['Authorization']);
        }
    }
    elseif( isset($headers['authorization']) ){
        if (strpos($headers['authorization'], 'Bearer') !== false) {
            $token = str_replace('Bearer ', '',$headers['authorization']);
        }
    }

    return $token;
}

function token_auth($token) {
    $token_info = DB::table('personal_access_tokens')->where('token', $token)->first();

    if ( empty($token_info) )
            return response()->json(['message' => 'Unauthenticated.'], 406);

    $user_info = $token_info->tokenable_type::where('id', $token_info->tokenable_id)->first();

    return $user_info;
}
