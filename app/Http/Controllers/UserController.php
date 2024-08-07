<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use DataTables;
use Storage;

class UserController extends Controller
{
    // public function index()
    // {
    //     $users = User::orderBy('id', 'DESC')->get();
    //     return response()->json($users);
    // }

    public function index()
    {
        $loggedInUserId = auth()->id();

        $users = User::where('id', '!=', $loggedInUserId)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($users);
    }

    // public function activate(User $user)
    // {
    //     $user->status = 'active';
    //     $user->save();

    //     return response()->json(['message' => 'User activated successfully.']);
    // }

    // public function deactivate(User $user)
    // {
    //     $user->status = 'deactivated';
    //     $user->save();
    //     return response()->json(['message' => 'User deactivated successfully.']);
    // }

    public function activate($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($user->status === 'inactive') {
            return response()->json(['error' => 'User is inactive and cannot be activated.'], 400);
        }

        if ($user->status === 'active') {
            return response()->json(['error' => 'User is already active.'], 400);
        }

        $user->status = 'active';
        $user->save();

        return response()->json(['success' => 'User activated successfully.'], 200);
    }

    public function deactivate($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($user->status === 'inactive') {
            return response()->json(['error' => 'User is inactive and cannot be deactivated.'], 400);
        }

        if ($user->status === 'deactivated') {
            return response()->json(['error' => 'User is already deactivated.'], 400);
        }

        $user->status = 'deactivated';
        $user->save();

        return response()->json(['success' => 'User deactivated successfully.'], 200);
    }


    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully.']);
    }
}
