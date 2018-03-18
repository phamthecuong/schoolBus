<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Service\LoginRequest;
use App\Http\Requests\Service\RegisterRequest;
use App\Http\Requests\Service\LoginFacebookRequest;
use App\Http\Requests\Service\ForgotPasswordRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Models\User;
use App\Models\Parents;
use App\Models\School;
use App\Models\Student;
use App\Models\SchoolAdmin;
use App\Models\LBM_conversation_user;
use App\Models\LBM_conversation;
use App\Models\Distance;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use Validator;

class UserController extends ApiController
{
    use ResetsPasswords;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        echo 1;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == -1)
        {
            return User::with("profile")->findOrFail(Auth::user()->id);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function postEmail(ForgotPasswordRequest $request)
    {
        try
        {
            $user = User::whereEmail($request->email)->first();
            if ($user)
            {
                $response = Password::sendResetLink(
                    array('email' => $user->email),
                    function($message) {
                        return $message->subject('Your Account Password');
                    }
                );

                switch ($response)
                {
                    case Password::RESET_LINK_SENT:
                        return response([
                            'description' => 'Success'
                        ]);
                        break;
                    case Password::INVALID_USER:
                        return $this->errorBadRequest([
                            'errors' => 'Invalid user'
                        ]);
                        break;
                }
            }
            return $this->errorBadRequest([
                'errors' => 'Invalid user'
            ]);
        }
        catch (\Exception $e)
        {
            dd($e);
        }

    }

    public function postLogin(LoginRequest $request) 
    {
        try
        {
            $user = null;
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            {
                $user = Auth::user();
                
            }
            else
            {
                // check phone number
                $parent = \App\Models\Parents::where('phone_number', $request->email)->get();
                if ($parent->count() == 1)
                {
                    if (\Hash::check($request->password, $parent->first()->users->first()->password))
                    {
                        $user = \Auth::loginUsingId($parent->first()->users->first()->id);
                    }
                }
                if (!isset($user))
                {
                    $driver = \App\Models\Driver::where('phone_number', $request->email)->get();
                    if ($driver->count() == 1)
                    {
                        if (\Hash::check($request->password, $driver->first()->users->first()->password))
                        {
                            $user = \Auth::loginUsingId($driver->first()->users->first()->id);
                        }
                    }
                }
            }

            if ($user && in_array($user->profile_type, ['parent', 'driver']))
            {
                $role = $user->profile_type;
                $user_profile = $user->with('profile')->find($user->id);

                return response([
                    'token' => $user->createToken('Laravel Personal Access Client')->accessToken,
                    'user' => $user_profile,
                    'role' => $role
                ]);
            }
            else
            {
                return $this->errorAuthenticationFailed([
                    'errors' => trans('auth.failed')
                ]);
            }
        }
        catch (\Exception $e)
        {
            dd($e);
        }
    }

    public function postRegister(RegisterRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $student = Student::where('code', $request->student_code)->first();
            if (!$student) 
            {
                $errors = new MessageBag(['student_code' => [trans('validate.student_code_invalid')]]);
                return $this->errorInvalid($errors);
            }
            else
            {
                $parents = new Parents();
                $parents->full_name = $request->name;
                $parents->phone_number = $request->phone_number;
                $parents->contact_email = $request->email;

                if (isset($request->facebook_id) && !empty($request->facebook_id))
                {
                    $media = \App\Models\Media::download_file("http://graph.facebook.com/" . $request->facebook_id . "/picture?type=square");

                    $parents->avatar_id = $media->id;
                }

                $parents->save();

                $user = new User();
                $user->email = @$request->email;
                $user->password = bcrypt($request->password);
                $user->profile_type = 'parent';
                $user->facebook_id = @$request->facebook_id;
                $user->profile_id = $parents->id;
                $user->save();

                $user_profile = $user->with('profile')->find($user->id);

                $parents->students()->attach($student->id);

                $distance = Distance::whereAbout(1000)->first();
                $parents->distances()->attach($distance->id);
                $this->_addConversation($user->id, $student->id);

                $user->syncRoleByCodeName('parent');


                DB::commit();
                // $user->roles()->sync(['f535191f4ed349e18f786f222fb5239a']);

                return response([
                    'token' => $user->createToken('Laravel Personal Access Client')->accessToken,
                    'user' => $user_profile,
                    'role' => 'parent'
                ]);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();
        }
    }

    public function postRegisterFacebook(LoginFacebookRequest $request)
    {
        try
        {
            $user = User::where('facebook_id', $request->facebook_id);
            $check = $user->first();
            
            if ($check)
            {
                $user_profile = $user->with('profile')->first();
                $role = $user_profile->profile_type;

                return response([
                    "token" => $user_profile->createToken('Laravel Personal Access Client')->accessToken,
                    "user" => $user_profile,
                    'role' => $role
                ]);
            }
            else 
            {
                return $this->errorInvalid([
                    'facebook_id' => 'facebook_id does not exist'
                ]);
            }
        }
        catch (\Exception $e)
        {
            dd($e);
        }
    }

    private function _addConversation($user_id, $student_id)
    {
        $school_id = Student::find($student_id)->classes[0]->school_id;
        $school_admin = SchoolAdmin::where('school_id', $school_id)->first()->users[0];
        $check = false;
        $data = LBM_conversation_user::select('conversation_id', \DB::raw('COUNT(*) as total_conversation'))
            ->whereIn('user_id', [$user_id, $school_admin->id])
            ->groupBy('conversation_id')->get();
        if (count($data) > 0)
        {
            foreach ($data as $data) 
            {
                if ($data->total_conversation == 2)
                {
                    $check = true;
                    break;
                }
            }
        }
        if (!$check) 
        {
            $conversation = new LBM_conversation();
            $conversation->created_by = $user_id;
            $conversation->save();
            $conversation->users()->sync([$user_id, $school_admin->id]);
        }
        else
        {
            return null;
        }
    }

    function getSchoolInfo()
    {
        try
        {
            $user = \Auth::user();
            if ($user->isParent())
            {
                $records = \App\Models\School::getListSchoolByParent();
                $dataset = [];
                foreach ($records as $key => $r) 
                {
                    if ($key == 0) continue;
                    $dataset[] = $r['name'];
                }
            } 
            elseif ($user->isDriver())
            {
                return [$user->profile->school->name];
            }
            else
            {
                return [];
            }
            return $dataset;
        }
        catch (\Exception $e)
        {
            return [$e->getMessage()];
        }
    }
}
