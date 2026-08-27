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
    $user->update([
        'status'=>'suspended'
    ]);
    $user->save();


    return [
        'message'=>'User suspended successifuuly',
        'user'=>$user
    ];
}

// unsuspend user
public function unsuspend(User $user)
{
    $user->update([
        'status'=>'active'
    ]);


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
   
    $user->update([
        'role'=>'admin'
    ]); 
    $role=$user->role;
     
    return [
        'message'=>'user role updated to admin',
        'role'=>$role
    ];
}
// demote role to user

public function demoteAdmin(User $user)
{
$user->update([
        'role'=>'user'
    ]); 


    $role=$user->role;
    return [
        'message'=>'Admin demoted to normal user ',
        'role'=>$role
    ];
}


}
