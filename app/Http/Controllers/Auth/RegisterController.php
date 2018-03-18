<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Parents;
use Illuminate\Http\Request;
use App\Models\LBM_conversation;
use App\Models\LBM_conversation_user;
use App\Models\SchoolAdmin;
use App\Models\Distance;
use DB;
use Socialite;
use App\Models\Media;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            // 'password' => array('required','min:8','confirmed','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])(?=.*(_|[^\w])).+$/'),
            'password' => array('required', 'min:6', 'confirmed'),
            'student_code' => 'required',
            'phone_number' => 'nullable|numeric|unique:drivers,phone_number|unique:parents,phone_number',
        ], $this->messages());
    }
    
    protected function messages()
    {
        return [
            'name.required' => trans('validate.name_required'),
            'name.max' => trans('validate.name_max'),
            'email.required' => trans('validate.email_required'),
            'email.email' => trans('validate.email'),
            'email.max' => trans('validate.email_max'),
            'email.unique' => trans('validate.email_unique'),
            'password.required' => trans('validate.password_required'),
            'password.min' => trans('validate.password_min'),
            'password.confirmed' => trans('validate.password_confirm'),
            'password.regex' => trans('validate.password_regex'),
            'student_code.required' => trans('validate.student_code_required'),
            'student_code.digits' => trans('validate.student_code_digits'),
            'phone_number.numeric' => trans('validate.phone_numeric'),
            'phone_number.unique' => trans('validation.phone_number_exist')

        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function postRegister(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $validator = $this->validator($request->all());
            if ($validator->fails())
            {
                $this->throwValidationException(
                   $request, $validator
                );
            }
            
            $student = Student::where('code', $request->student_code)->first();
            if (!$student)
            {
                return redirect('register')->withInput()->with([
                    'flash_level' => 'danger'
                ])->withErrors([
                    'student_code' => trans('auth.code_invalid')
                ]);
            }
            else
            {
                // echo "<pre>";
                // print_r($request->facebook_avatar);
                // echo "</pre>";
                $parents = new Parents();
                //$parents->id = $user->id;
                $parents->full_name = $request->name;
                $parents->phone_number = $request->phone_number;
                if (isset($request->facebook_avatar))
                {
                    $parents->avatar()->associate(Media::download_file($request->facebook_avatar));    
                }
                $parents->save();

                $student_parent = new StudentParent();
                $student_parent->student_id = $student->id;
                $student_parent->parent_id = $parents->id;
                $student_parent->save();

                $user = new User();
                
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->profile_type = 'parent';
                $user->facebook_id = $request->facebook_id;

                $user->profile_id = $parents->id;
                $user->save();

                $distance = Distance::whereAbout(1000)->first();
                $parents->distances()->attach($distance->id);
                
                $this->_addConversation($user->id, $student->id);

                $user->syncRoleByCodeName('parent');

                DB::commit();

                \Auth::loginUsingId($user->id);
                return redirect('home')->with([
                    'flash_level' => 'success',
                    'flash_message' => trans('auth.register_success')
                ]);
            }
        }
        catch (Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
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
}
