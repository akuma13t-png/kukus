<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function stats()
    {
        // Cek status koneksi database untuk ditampilkan di halaman Stats
        try {
            DB::connection()->getPdo();
            $dbStatus = 'Online';
            $dbLatency = 'Active'; 
        } catch (\Exception $e) {
            $dbStatus = 'Offline';
            $dbLatency = 'Error';
        }

        return view('pages.stats', compact('dbStatus', 'dbLatency'));
    }
}