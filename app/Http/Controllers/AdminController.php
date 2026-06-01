<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Carousel;
use App\Models\Extra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{
    public function AdminHome()
    {
        $user = Auth::user();
        return view('admin.home', compact('user'));
    }

    public function ManageUsers()
    {
       $users = User::where ('id', '!=', Auth::id())->get();
       $deletedUsers = User::onlyTrashed()->get();
        return view('admin.users', compact('users', 'deletedUsers'));
    }

    public function UpdateRole(User $user , Request $request)
{
    $user->role = $request->role;
    $user->save();
    return redirect()->back()->with('success', 'User role updated successfully.');
}

    public function DeleteUser(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function RestoreUser(int $id)
    {   $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->back()->with('success', 'User restored successfully.');
    }

    public function PermanentDeleteUser(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'User permanently deleted successfully.');
    }

}