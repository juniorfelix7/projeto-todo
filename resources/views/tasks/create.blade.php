<x-layout page="Criar tarefa">
    <x-slot:btn>
        <a href="{{route('home')}}" class="btn btn-primary">
            Voltar
        </a>
    </x-slot:btn>    
    <section id="create_task_section">
        <h1>Criar Tarefa</h1>
            <form action="{{ route('task.store') }}" method="POST">
                @csrf
                <div class="inputArea">
                    <label for="title">
                        Título da tarefa
                    </label>
                    <input name="title" placeholder="Digite o título da tarefa" required />
                </div>
                <div class="inputArea">
                    <label for="title">
                        Data de Realização
                    </label>
                    <input type="date" name="due_date"  required />
                </div>
                <div class="inputArea">
                    <label for="category">
                        Categoria
                    </label>
                    <select name="category" required>
                        <option selected disabled value="">Selecione a Categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->title }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="inputArea">
                    <label for="title">
                       Descrição da tarefa
                    </label>
                    <textarea name="description" placeholder="Digite uma descrição para sua tarefa"></textarea>
                </div>
                <div class="inputArea">
                    <button type="submit" class="btn btn-primary">
                        Criar Tarefa
                    </button>
                </div>
            </form>
    </section>
</x-layout>