<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil menu root dan relasi children
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('order'); // Urutkan children
            }])
            ->orderBy('order') // Urutkan menu utama
            ->get();
        return view('welcome', compact('menus'));
    }

    public function store(Request $request)
    {
        // Validasi data JSON
        $request->validate([
            'menus' => 'required|array',
        ]);

        $menus = $request->input('menus');

        // Hapus semua menu lama jika diperlukan (opsional)
        Menu::truncate();

        // Simpan menu baru
        $this->saveMenus($menus, null);

        return response()->json(['success' => 'Menu berhasil disimpan']);
    }

    private function saveMenus(array $menus, $parentId = null)
    {
        foreach ($menus as $menu) {
            // Pastikan data menu valid
            if (!isset($menu['id']) || !isset($menu['order']) || !isset($menu['children'])) {
                continue; // Lewati jika data tidak valid
            }

            // Simpan atau perbarui menu dengan urutan
            $menuModel = Menu::updateOrCreate(
                ['id' => $menu['id'] ?? null], // Jika ID kosong, buat menu baru
                [
                    'name' => $menu['name'], // Nama dari JSON
                    'parent_id' => $parentId, // Parent ID
                    'order' => $menu['order'] // Urutan menu
                ]
            );

            // Rekursif untuk children
            if (!empty($menu['children']) && is_array($menu['children'])) {
                $this->saveMenus($menu['children'], $menuModel->id);
            }
        }
    }
}
