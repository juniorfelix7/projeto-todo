<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function create(Request $request){
        return view('categories.create');
    }

    public function store(Request $request){
        // Validação dos dados
        $request->validate([
            'title' => 'required|string|max:255|unique:categories,title',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        // Criar a categoria
        Category::create([
            'title' => $request->title,
            'color' => $request->color,
            'user_id' => 1 // Por enquanto usando user_id 1, depois pode ser auth()->id()
        ]);

        return redirect()->route('home')->with('success', 'Categoria criada com sucesso!');
    }
}
