<x-layout page="Criar categoria">
    <x-slot:btn>
        <a href="{{route('home')}}" class="btn btn-primary">
            Voltar
        </a>
    </x-slot:btn>    
    <section id="create_category_section">
        <h1>Criar Categoria</h1>
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="inputArea">
                    <label for="title">
                        Nome da categoria
                    </label>
                    <input name="title" placeholder="Digite o nome da categoria" required />
                    @error('title')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="inputArea">
                    <label for="color">
                        Cor da categoria
                    </label>
                    <input type="color" name="color" value="#007bff" required />
                    @error('color')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="inputArea">
                    <button type="submit" class="btn btn-primary">
                        Criar Categoria
                    </button>
                </div>
            </form>
    </section>
</x-layout>
