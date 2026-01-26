<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates
     */
    public function index(Request $request)
    {
        $query = Template::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_template', 'ILIKE', "%{$search}%")
                  ->orWhere('keterangan', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_template' => 'required|string|max:255',
            'konten' => 'required|string',
            'tipe' => 'required|in:perjanjian,laporan',
            'is_active' => 'boolean',
            'keterangan' => 'nullable|string',
        ]);

        Template::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil ditambahkan!');
    }

    /**
     * Display the specified template
     */
    public function show(Template $template)
    {
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'nama_template' => 'required|string|max:255',
            'konten' => 'required|string',
            'tipe' => 'required|in:perjanjian,laporan',
            'is_active' => 'boolean',
            'keterangan' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil diupdate!');
    }

    /**
     * Remove the specified template
     */
    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil dihapus!');
    }

    /**
     * Duplicate template
     */
    public function duplicate(Template $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->nama_template = $template->nama_template . ' (Copy)';
        $newTemplate->is_active = false;
        $newTemplate->save();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template berhasil diduplikasi!');
    }
}
