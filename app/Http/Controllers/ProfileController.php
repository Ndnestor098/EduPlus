<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Role;
use App\Models\RolesUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected $role;

    public function __construct()
    {
        $this->role = [];
        $rol = RolesUser::where('user_id', auth()->user()->id)->get();
        $valor = array();

        foreach ($rol as $key) {
            array_push($valor, $key->role_id);
        }

        foreach ($valor as $key) {
            array_push($this->role, Role::find($key)->name);
        }
    }

    public function role(){
        $array = [true, true];

        if(!in_array('director', $this->role)){
            $array[0] = false;
        }
        if(!in_array('admin', $this->role)){
            $array[1] = false;
        }

        if($array[0] || $array[1]){
            // Se estan cumpliendo los valores
        }else{
            return true;
        }
    }

    public function edit(Request $request): View
    {

        return view('profile.edit', [
            'user' => $request->user(),
            'role' => $this->role
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
