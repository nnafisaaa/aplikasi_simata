<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::with('unit')->paginate(10); // Ambil semua user beserta unitnya dengan pagination
        return view('users.index', compact('users'));
    }
}
