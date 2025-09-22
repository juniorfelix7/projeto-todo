<x-layout>
    <x-slot:btn>
        <a href="{{route('task.create')}}" class="btn btn-primary">
            Criar Tarefa
        </a>
        <a href="{{route('category.create')}}" class="btn btn-primary" style="margin-left:10px;">
            Criar Categoria
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Sair
            </button>
        </form>
    </x-slot:btn>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

<section class="graph">
    <div class="graph_header">
            <div class="graph_header-date">
                <img src="/assets/images/icon-prev.png" onclick="changeDate(-1)" alt="Data anterior" />
                    {{ $selectedDate->format('d \d\e F') }}
                <img src="/assets/images/icon-next.png" onclick="changeDate(1)" alt="Próxima data" />
            </div>
        <h2>Progresso do dia</h2>
        <div class="progress_bar_container">
            <div class="progress_bar">
                <div class="progress_bar_fill" style="width: {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%"></div>
            </div>
            <div class="progress_bar_label">
                <span class="progress_text">{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}% concluído</span>
            </div>
        </div>
            
                    
        </div>
    <div class="graph_header-subtitle"> Tarefas: <b>{{ $completedTasks }}/{{ $totalTasks }}</b></div>
        <div class="progress_chart">
            <div class="chart_container">
                <svg class="progress_ring" width="120" height="120">
                    <circle class="progress_ring_circle" cx="60" cy="60" r="50" fill="transparent" stroke="#e0e0e0" stroke-width="8"/>
                    <circle class="progress_ring_circle progress_ring_circle_fill" cx="60" cy="60" r="50" fill="transparent" stroke="#6143ff" stroke-width="8" stroke-linecap="round"/>
                </svg>
                <div class="chart_percentage">
                    <span class="percentage_number">{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}</span>
                    <span class="percentage_symbol">%</span>
                </div>
            </div>
        </div>
            <div class="task_left_footer">
                <img src="/assets/images/icon-info.png" alt="Tarefas restantes"/>
                    Restam {{ $pendingTasks }} tarefas a serem realizadas 
            </div>
</section>
<section class="list">
    <div class="list_header">
        <select class="list_header-select" id="taskFilter" onchange="filterTasks()">
            <option value="1" {{ $currentFilter == 1 ? 'selected' : '' }}>Todas as tarefas</option>
            <option value="2" {{ $currentFilter == 2 ? 'selected' : '' }}>Tarefas concluídas</option>
            <option value="3" {{ $currentFilter == 3 ? 'selected' : '' }}>Tarefas pendentes</option>
        </select>
    </div>
    <div class="task_list">
        @foreach($tasks as $task)
            <x-task :data=$task />
        @endforeach
            
    </div>      
</section>

<script>
function changeDate(direction) {
    // Obter a data atual da URL ou usar a data de hoje
    const urlParams = new URLSearchParams(window.location.search);
    let currentDate = urlParams.get('date') || new Date().toISOString().split('T')[0];
    
    // Criar objeto Date e adicionar/subtrair dias
    const date = new Date(currentDate);
    date.setDate(date.getDate() + direction);
    
    // Formatar para YYYY-MM-DD
    const newDate = date.toISOString().split('T')[0];
    
    // Obter o filtro atual
    const currentFilter = document.getElementById('taskFilter').value;
    
    // Redirecionar para a nova data mantendo o filtro
    window.location.href = '{{ route("home") }}?date=' + newDate + '&filter=' + currentFilter;
}

function filterTasks() {
    const filter = document.getElementById('taskFilter').value;
    const urlParams = new URLSearchParams(window.location.search);
    const currentDate = urlParams.get('date') || new Date().toISOString().split('T')[0];
    
    // Fazer requisição AJAX para filtrar as tarefas
    fetch(`{{ route("home") }}?date=${currentDate}&filter=${filter}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Atualizar a lista de tarefas
        updateTaskList(data.tasks);
        
        // Atualizar as estatísticas
        updateStatistics(data);
    })
    .catch(error => {
        console.error('Erro ao filtrar tarefas:', error);
        // Em caso de erro, recarregar a página com o filtro
        window.location.href = `{{ route("home") }}?date=${currentDate}&filter=${filter}`;
    });
}

function updateTaskList(tasks) {
    const taskList = document.querySelector('.task_list');
    
    if (tasks.length === 0) {
        taskList.innerHTML = '<div class="no-tasks">Nenhuma tarefa encontrada para o filtro selecionado.</div>';
        return;
    }
    
    // Criar HTML das tarefas
    let tasksHTML = '';
    tasks.forEach(task => {
        const isChecked = task.is_done ? 'checked' : '';
        const categoryTitle = task.category ? task.category.title : '';
        
        tasksHTML += `
            <div class="task">
                <div class="title">
                    <input type="checkbox" ${isChecked} data-task-id="${task.id}" onchange="toggleTaskStatus(this)" />
                    <div class="task_title">${task.title || ''}</div>
                </div>
                <div class="priority">
                    <div class="sphere"></div>
                    <div>${categoryTitle}</div>
                </div>
                <div class="actions">
                    <a href="{{ route('task.edit', ['id' => '']) }}${task.id}">
                        <img src="/assets/images/icon-edit.png" alt="Editar"/>
                    </a>
                    <a href="{{ route('task.delete', ['id' => '']) }}${task.id}" onclick="return confirmDelete('${task.title}')">
                        <img src="/assets/images/icon-delete.png" alt="Excluir"/>
                    </a>
                </div>
            </div>
        `;
    });
    
    taskList.innerHTML = tasksHTML;
}

function updateStatistics(data) {
    // Atualizar o subtítulo com as estatísticas
    const subtitle = document.querySelector('.graph_header-subtitle');
    if (subtitle) {
        subtitle.innerHTML = `Tarefas: <b>${data.completedTasks}/${data.totalTasks}</b>`;
    }
    
    // Atualizar o gráfico de progresso
    updateProgressChart(data.completedTasks, data.totalTasks);
    
    // Atualizar o rodapé com tarefas restantes
    const footer = document.querySelector('.task_left_footer');
    if (footer) {
        footer.innerHTML = `
            <img src="/assets/images/icon-info.png" alt="Tarefas restantes"/>
            Restam ${data.pendingTasks} tarefas a serem realizadas
        `;
    }
}

function updateProgressChart(completedTasks, totalTasks) {
    const percentageNumber = document.querySelector('.percentage_number');
    const progressCircle = document.querySelector('.progress_ring_circle_fill');
    const progressBarFill = document.querySelector('.progress_bar_fill');
    const progressText = document.querySelector('.progress_text');
    
    if (!percentageNumber || !progressCircle) {
        return;
    }
    
    // Calcular porcentagem
    const percentage = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
    
    // Atualizar o número da porcentagem no gráfico circular
    percentageNumber.textContent = percentage;
    
    // Calcular o comprimento do arco do círculo
    const radius = 50;
    const circumference = 2 * Math.PI * radius;
    const strokeDasharray = (percentage / 100) * circumference;
    
    // Atualizar o círculo de progresso
    progressCircle.style.strokeDasharray = `${strokeDasharray} ${circumference}`;
    
    // Atualizar a barra de progresso
    if (progressBarFill) {
        progressBarFill.style.width = `${percentage}%`;
    }
    
    // Atualizar o texto da barra de progresso
    if (progressText) {
        progressText.textContent = `${percentage}% concluído`;
    }
}

function toggleTaskStatus(checkbox) {
    const taskId = checkbox.getAttribute('data-task-id');
    const isDone = checkbox.checked;
    
    if (!taskId) {
        console.error('ID da tarefa não encontrado');
        return;
    }
    
    // Desabilitar o checkbox temporariamente para evitar múltiplos cliques
    checkbox.disabled = true;
    
    // Fazer requisição AJAX para atualizar o status
    fetch('{{ route("task.updateStatus") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            task_id: taskId,
            is_done: isDone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar as estatísticas na página
            updatePageStatistics();
            
            // Mostrar mensagem de sucesso (opcional)
            showNotification(data.message, 'success');
        } else {
            // Reverter o estado do checkbox em caso de erro
            checkbox.checked = !isDone;
            showNotification('Erro ao atualizar tarefa', 'error');
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar tarefa:', error);
        // Reverter o estado do checkbox em caso de erro
        checkbox.checked = !isDone;
        showNotification('Erro ao atualizar tarefa', 'error');
    })
    .finally(() => {
        // Reabilitar o checkbox
        checkbox.disabled = false;
    });
}

function updatePageStatistics() {
    // Obter a data atual da URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentDate = urlParams.get('date') || new Date().toISOString().split('T')[0];
    
    // Fazer requisição para obter estatísticas atualizadas
    fetch(`{{ route("home") }}?date=${currentDate}&filter=1`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        updateStatistics(data);
    })
    .catch(error => {
        console.error('Erro ao atualizar estatísticas:', error);
    });
}

function showNotification(message, type) {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Adicionar estilos
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        ${type === 'success' ? 'background-color: #28a745;' : 'background-color: #dc3545;'}
    `;
    
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 100);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Função para confirmar exclusão de tarefa
function confirmDelete(taskTitle) {
    return confirm(`Tem certeza que deseja excluir a tarefa "${taskTitle}"?\n\nEsta ação não pode ser desfeita.`);
}

// Inicializar o gráfico quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    // Obter os valores iniciais do PHP
    const completedTasks = {{ $completedTasks }};
    const totalTasks = {{ $totalTasks }};
    
    // Atualizar o gráfico com os valores iniciais
    updateProgressChart(completedTasks, totalTasks);
});
</script>
</x-layout>