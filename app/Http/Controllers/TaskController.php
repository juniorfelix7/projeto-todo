<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;

class TaskController extends Controller
{
    //
    public function index(){

    }

    public function create(Request $request){
        $categories = Category::all();
        return view ('tasks.create', ['categories' => $categories]);
    }

    public function store(Request $request){
        // Validação dos dados
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'category' => 'required|string'
        ]);

        // Buscar ou criar categoria
        $category = Category::firstOrCreate(
            ['title' => $request->category],
            [
                'title' => $request->category,
                'color' => '#007bff', // Cor padrão
                'user_id' => 1 // Por enquanto usando user_id 1, depois pode ser auth()->id()
            ]
        );

        // Criar a task
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => 1, // Por enquanto usando user_id 1, depois pode ser auth()->id()
            'category_id' => $category->id,
            'is_done' => false
        ]);

        return redirect()->route('home')->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Request $request){
        $taskId = $request->get('id');
        
        if (!$taskId) {
            return redirect()->route('home')->with('error', 'ID da tarefa não fornecido.');
        }
        
        $task = Task::with('category')->findOrFail($taskId);
        $categories = Category::all();
        
        return view('tasks.edit', [
            'task' => $task,
            'categories' => $categories
        ]);
    }

    public function update(Request $request){
        // Validação dos dados
        $request->validate([
            'task_id' => 'required|integer|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'category' => 'required|string',
            'is_done' => 'nullable|boolean'
        ]);

        // Buscar a tarefa
        $task = Task::findOrFail($request->task_id);
        
        // Buscar ou criar categoria
        $category = Category::firstOrCreate(
            ['title' => $request->category],
            [
                'title' => $request->category,
                'color' => '#007bff', // Cor padrão
                'user_id' => 1 // Por enquanto usando user_id 1, depois pode ser auth()->id()
            ]
        );

        // Atualizar a tarefa
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'category_id' => $category->id,
            'is_done' => $request->has('is_done') ? true : false
        ]);

        return redirect()->route('home')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function delete(Request $request){
        return redirect(route('home'));   
    }

    public function updateStatus(Request $request){
        $request->validate([
            'task_id' => 'required|integer|exists:tasks,id',
            'is_done' => 'required|boolean'
        ]);

        $task = Task::findOrFail($request->task_id);
        $task->is_done = $request->is_done;
        $task->save();

        // Se for uma requisição AJAX, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $request->is_done ? 'Tarefa marcada como concluída!' : 'Tarefa marcada como pendente!',
                'task' => $task->load('category')
            ]);
        }

        return redirect()->back()->with('success', $request->is_done ? 'Tarefa marcada como concluída!' : 'Tarefa marcada como pendente!');
    }
}
