<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class UserController extends Controller
{
  // all users
  public function index()
  {
    $users=User::latest()->paginate(10);
    return response()->json($users);
  }
  //access one user
  public function oneUser(User $user)
  {
    return response()->json($user);
  }
// update user
public function update(Request $request,User $user)
{
    $update=$user->update([
        'name'=>$request->name,
        'email'=>$request->email,
        'role'=>$request->role,
        'phone'=>$request->phone
    ]);

    return [
        'message'=>'user profile updated',
        'profile'=>$user
    ];
}
// soft delete
public function softDelete(User $user)
{
     $admin=auth()->user();
    if($admin->role !== 'admin' && $admin->role !== 'super_admin'){
        abort(403,'Unauthorised action');
    }
    $user->update([
        'status'=>'suspended'
    ]);
    //$user->save();

// log out event
    AuditLog::Log(
        $admin->id,
        'User Suspension',
        $admin->name.' suspended user '.$user->name
    );
//response
    return [
        'message'=>'User suspended successifuuly',
        'user'=>$user
    ];
}

// unsuspend user
public function unsuspend(User $user)
{

    $admin=auth()->user();
    if($admin->role !== 'admin' && $admin->role !== 'super_admin'){
        abort(403,'Unauthorised action');
    }
    $user->update([
        'status'=>'active'
    ]);

// log out event
       AuditLog::Log(
     auth()->id(),
    'User Unsuspension',
    auth()->user()->name.' unsuspended '.$user->name
);

    return response()->json([
        'message'=>'User unsuspend successiful',
        'user'=>$user
    ]);

}


// view suspended users
public function viewSuspended()
{
    $suspended=User::where('status','suspended')->lates();
    return response()->json($suspended);
}

// change role to admin
public function makeAdmin(User $user)
{
    $admin=auth()->user();
    if($admin->role !== 'admin' && $admin->role !== 'super_admin'){
        abort(403,'Unauthorised action');
    }
    $user->update([
        'role'=>'admin'
    ]); 
    $role=$user->role;
     
    // log out event
        AuditLog::Log(
            auth()->user()->id,
            'Admin Creation',
            auth()->user()->name.' made '.$user->name.' an admin'
        );

    return [
        'message'=>'user role updated to admin',
        'role'=>$role
    ];
}
// demote role to user

public function demoteAdmin(User $user)
{
     $admin=auth()->user();
    if($admin->role !== 'admin' && $admin->role !== 'super_admin'){
        abort(403,'Unauthorised');
    }
    $user->update([
        'role'=>'user'
    ]); 


    $role=$user->role;

    // log out event
        AuditLog::Log(
            auth()->user()->id,
            'Admin Demotion',
            auth()->user()->name.' demoted '.$user->name.' to a user'
        );
// response

    return [
        'message'=>'Admin demoted to normal user ',
        'role'=>$role
    ];
}


}
