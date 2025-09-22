<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class HomeController extends Controller
{
    //
    public function index(Request $request){
        // Obter a data da requisição ou usar a data atual
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Obter o filtro da requisição (1 = todas, 2 = concluídas, 3 = pendentes)
        $filter = $request->get('filter', 1);
        
        // Filtrar tarefas pela data selecionada e usuário autenticado
        $tasksQuery = Task::whereDate('due_date', $selectedDate->format('Y-m-d'))
            ->where('user_id', auth()->id())
            ->with('category');
        
        // Aplicar filtro baseado no parâmetro
        switch($filter) {
            case 2: // Tarefas concluídas
                $tasksQuery->where('is_done', true);
                break;
            case 3: // Tarefas pendentes
                $tasksQuery->where('is_done', false);
                break;
            default: // Todas as tarefas (case 1)
                break;
        }
        
        $tasks = $tasksQuery->get();
        
        // Calcular estatísticas (sempre baseadas em todas as tarefas da data do usuário)
        $allTasks = Task::whereDate('due_date', $selectedDate->format('Y-m-d'))
            ->where('user_id', auth()->id())
            ->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('is_done', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
        
        // Se for uma requisição AJAX, retornar apenas as tarefas filtradas
        if ($request->ajax()) {
            return response()->json([
                'tasks' => $tasks,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks,
                'pendingTasks' => $pendingTasks
            ]);
        }
        
        return view('home', [
            'tasks' => $tasks,
            'selectedDate' => $selectedDate,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'currentFilter' => $filter
        ]);
    }
}
