<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{

    /**
     * Show The Dashboard
     *
     * @return View
     */
    public function show(): View
    {
        $app = Setting::first();

        return view('dashboard', ['app' => $app]);
    }

    /**
     * Regenrate API KEY
     *
     * @return RedirectResponse
     */
    public function regenrateKey(): RedirectResponse
    {
        try {
            $app = Setting::first();
            $app->key = bcrypt((string) rand(51313242343411, 9999999999999999));
            $app->save();
        } catch (Exception $e) {
            throw new Exception('Can\'t generate new key.');
        }

        session()->flash('success_message', 'API KEY Regenrated Successfuly.');
        return to_route('dashboard.show');
    }
}
