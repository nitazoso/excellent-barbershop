<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarbershopSetting;
use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home.index', [
            'barbers' => Barber::query()->where('is_active', true)->get(),
            'services' => Service::query()->where('is_active', true)->get(),
            'galleries' => Gallery::query()->where('is_active', true)->latest()->get(),
            'settings' => BarbershopSetting::query()->first(),
        ]);
    }
}
