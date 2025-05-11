<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function index()
    {
        $sites = auth()->user()->sites;
        return view('admin.sites.index', compact('sites'));
    }

    public function create()
    {
        return view('admin.sites.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'domain' => 'required|url',
        ]);

        $apiKey = auth()->user()->createApiKey(Str::slug($data['name']));

        auth()->user()->sites()->create([
            'name' => $data['name'],
            'domain' => $data['domain'],
            'api_key_id' => $apiKey->id,
        ]);

        return redirect()->route('admin.sites.index')->with('success', 'Site created.');
    }
}
