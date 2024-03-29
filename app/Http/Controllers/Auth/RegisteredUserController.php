<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:domains'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $tenant = Tenant::create([
            'id' => $user->id,
            'company' => $request->company,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$tenant) {
            $user->delete();
        }

        $domain = $tenant->domains()->create([
            'domain' => $request->domain,
        ]);

        if (!$domain) {
            $tenant->delete();
            $user->delete();
        }

        event(new Registered($user));

        Auth::login($user);

        // Let's say we want to be redirected to the dashboard
        // after we're logged in as the impersonated user.
        $redirectUrl = '/dashboard';

        $token = tenancy()->impersonate($tenant, $user->id, $redirectUrl);

        // Note: This is not part of the package, it's up to you to implement
        // a concept of "primary domains" if you need them. Or maybe you use
        // one domain per tenant. The package lets you do anything you want.
        return redirect('http://' . $domain->domain . '.' . config('tenancy.central_domains')[0] . '/impersonate/' . $token->token);

        // return redirect(RouteServiceProvider::HOME);
    }
}
