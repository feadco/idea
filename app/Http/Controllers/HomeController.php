<?php

namespace App\Http\Controllers;

use App\Components\Businame;
use App\Components\Yoncu;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function ideas(Request $request)
    {
        $ideas = collect();

        for ($i = 0; $i < 30; $i++) {
            $ideas[] = [
                'name' => Businame::generate($request->q),
                'domains' => [],
            ];
        }

        return $ideas->unique()->values();
    }

    public function checkDomain($domain)
    {
        return Yoncu::check([
            "{$domain}.com",
            "{$domain}.co",
            "{$domain}.net",
        ]);
    }
}
