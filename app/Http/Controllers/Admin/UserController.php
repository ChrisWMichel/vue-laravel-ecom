<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return Inertia::render('admin/users/index', [
            'users' => $users
        ]);
    }
 
    

    public function delete(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
       // $users = User::latest()->paginate(10);

        // return Inertia::render('admin/user/index')->with([
        //     'success' => 'User has been deleted successfully'
        // ]);
    }
}
