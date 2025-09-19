<x-layout page="Editar tarefa">
    <x-slot:btn>
        <a href="{{route('home')}}" class="btn btn-primary">
            Voltar
        </a>
    </x-slot:btn>    
    <section id="edit_task_section">
        <h1>Editar Tarefa</h1>
        <form action="{{ route('task.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            
            <div class="inputArea">
                <label for="title">
                    Título da tarefa
                </label>
                <input name="title" placeholder="Digite o título da tarefa" value="{{ old('title', $task->title) }}" required />
                @error('title')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="due_date">
                    Data de Realização
                </label>
                <input type="date" name="due_date" value="{{ old('due_date', \Carbon\Carbon::parse($task->due_date)->format('Y-m-d')) }}" required />
                @error('due_date')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="category">
                    Categoria
                </label>
                <select name="category" required>
                    <option disabled value="">Selecione a Categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->title }}" 
                            {{ old('category', $task->category->title) == $category->title ? 'selected' : '' }}>
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="description">
                   Descrição da tarefa
                </label>
                <textarea name="description" placeholder="Digite uma descrição para sua tarefa">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="is_done">
                    Status da tarefa
                </label>
                <div class="checkbox-container">
                    <input type="checkbox" name="is_done" id="is_done" value="1" 
                        {{ old('is_done', $task->is_done) ? 'checked' : '' }} />
                    <label for="is_done" class="checkbox-label">Marcar como concluída</label>
                </div>
            </div>
            
            <div class="inputArea">
                <button type="submit" class="btn btn-primary">
                    Atualizar Tarefa
                </button>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </section>
</x-layout>