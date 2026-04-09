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
        $tahun2 = Setting::get('tahun_perjanjian_2', date('Y') + 1);
        
        return view('admin.settings.index', compact('tahun1', 'tahun2'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'tahun_perjanjian_1' => 'required|integer|min:2020|max:2050',
            'tahun_perjanjian_2' => 'required|integer|min:2020|max:2050|different:tahun_perjanjian_1',
        ], [
            'tahun_perjanjian_2.different' => 'Tahun kedua harus berbeda dengan tahun pertama'
        ]);
        
        Setting::set('tahun_perjanjian_1', $request->tahun_perjanjian_1, 'Tahun Perjanjian Pertama');
        Setting::set('tahun_perjanjian_2', $request->tahun_perjanjian_2, 'Tahun Perjanjian Kedua');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan tahun berhasil diupdate!');
    }
}
