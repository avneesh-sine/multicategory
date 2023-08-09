<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Validator;

use DataTables;
use Form;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){        
        return view('admin/users/index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request){
        $role_id = $request->input('role_id');

        $users = User::with(['roles','media'])->where(function($query){
            $query->whereHas('roles', function($query){
                $query->where('id','!=' ,config('constants.ROLE_TYPE_SUPERADMIN_ID'));
            });
            $query->orWhereDoesntHave('roles');
        });

        if(intval($role_id) > 0)
            $users->whereHas('roles', function($query) use ($role_id) {
                $query->where('id', $role_id);
            });

        $users = $users->select(DB::raw(with(new User)->getTable().'.*'));

        return DataTables::of($users)
            ->orderColumn('profile_picture', '-name $1')
            ->editColumn('created_at', function($user){
                return date(config('constants.DATETIME_FORMAT'), strtotime($user->created_at));
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $query->whereRaw("LOWER(DATE_FORMAT(created_at,'".config('constants.MYSQL_DATETIME_FORMAT')."')) like ?", ["%$keyword%"]);
            })
            ->editColumn('role', function($user){
                return ucwords($user->roles->pluck('name')->implode(', '));
            })
            ->editColumn('profile_picture', function ($user) {
                $image = asset($user->getFirstMediaUrl('profile_picture'));
                return '<img src="'.$image.'" width="100">';
            })
            ->addColumn('name', function ($user) {                
                return $user->name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $query->where(function($query) use ($keyword){
                    $query->whereRaw("LOWER(first_name) like ?", ["%$keyword%"])
                          ->orWhereRaw("LOWER(last_name) like ?", ["%$keyword%"]);
                });
            })
            ->orderColumn('name', 'first_name $1')
            ->editColumn('is_active', function ($user) {
                if($user->is_active == TRUE )
                {
                    return "<a href='".route('admin.users.status',$user)."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.users.status',$user)."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })
            ->addColumn('action', function ($user) {
                return
                        // edit
                        '<a href="'.route('admin.users.edit',[$user->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
                        // Delete
                          Form::open(array(
                                      'style' => 'display: inline-block;',
                                      'method' => 'DELETE',
                                       'onsubmit'=>"return confirm('Do you really want to delete?')",
                                      'route' => ['admin.users.destroy', $user->id])).
                          ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                          Form::close();
            })
            ->rawColumns(['profile_picture','is_active','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $roles = Role::where('id', '!=', config('constants.ROLE_TYPE_SUPERADMIN_ID'))->get()->pluck('name', 'id')->map(function($value, $key){
            return ucwords($value);
        });
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'role_id'           => 'required', 
            'first_name'              => 'required', 
            'last_name'              => 'required', 
            'email'             => 'required|email|unique:'.with(new User)->getTable().',email',
            'profile_picture'=>[
                'file',
                'image',
                'max:'.(config('media-library.max_file_size') / 1024),
            ],
            'password'          => 'required|confirmed'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);

            $role = Role::where('id', $request->role_id)->first();
            if (isset($role->id)) {
                $user->assignRole($role);
            }

            if ($request->hasFile('profile_picture')){
                $file = $request->file('profile_picture');
                $customname = time() . '.' . $file->getClientOriginalExtension();
                $user->addMedia($file)
                        ->usingFileName($customname)
                        ->toMediaCollection('profile_picture');
            }

            //event(new Registered($user));

            $request->session()->flash('success',__('global.messages.add'));
            return redirect()->route('admin.users.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user){
        return view('admin.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user){
        $rules = [
            'first_name'              => 'required', 
            'last_name'              => 'required', 
            'email'             => 'required|email|unique:'.with(new User)->getTable().',email,'.$user->getKey(),
            'profile_picture'=>[
                'file',
                'image',
                'max:'.(config('media-library.max_file_size') / 1024),
            ]
        ];
       
        if (isset($request->reset_password) && $request->reset_password==TRUE) {
            $rules['password'] = 'required|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();

            if (isset($request->reset_password) && $request->reset_password==TRUE) {
                $data['password'] = Hash::make($request->password);
            }else{
                unset($data['password']);
            }
             
            $user->update($data);

            if ($request->hasFile('profile_picture')){
                $file = $request->file('profile_picture');
                $customname = time() . '.' . $file->getClientOriginalExtension();
                $user->addMedia($file)
                        ->usingFileName($customname)
                        ->toMediaCollection('profile_picture');
            }
            $request->session()->flash('success',__('global.messages.update'));
            return redirect()->route('admin.users.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Change status the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, User $user){
        if (isset($user->is_active) && $user->is_active==FALSE) {
            $user->update(['is_active'=>TRUE]);
            $request->session()->flash('success',__('global.messages.activate'));
        }else{
            $user->update(['is_active'=>FALSE]);
            $request->session()->flash('danger',__('global.messages.deactivate'));
        }
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user){
        $user->forceDelete();
        $request->session()->flash('error',__('global.messages.delete'));
        return redirect()->route('admin.users.index');
    }
}
