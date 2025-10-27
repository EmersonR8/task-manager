<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role'); // ?role=developer
        if ($role) {
            $users = User::where('role', $role)->get();
        } else {
            $users = User::all();
        }
        return response()->json($users);
    }
}
