<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $tahun1 = Setting::get('tahun_perjanjian_1', date('Y'));
        
        return view('admin.settings.index', compact('tahun1'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'tahun_perjanjian_1' => 'required|integer|min:2020|max:2050',
        ]);
        
        Setting::set('tahun_perjanjian_1', $request->tahun_perjanjian_1, 'Tahun Perjanjian');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan tahun berhasil diupdate!');
    }
}
