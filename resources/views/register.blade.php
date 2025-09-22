<x-layout page="Cadastro">
    <x-slot:btn>
        <a href="{{route('login')}}" class="btn btn-primary">
            Login
        </a>
    </x-slot:btn>    
    
    <section id="register_section">
        <h1>Criar Conta</h1>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="inputArea">
                <label for="name">
                    Nome Completo
                </label>
                <input type="text" name="name" placeholder="Digite seu nome completo" value="{{ old('name') }}" required />
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="email">
                    E-mail
                </label>
                <input type="email" name="email" placeholder="Digite seu e-mail" value="{{ old('email') }}" required />
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="password">
                    Senha
                </label>
                <input type="password" name="password" placeholder="Digite sua senha (mínimo 6 caracteres)" required />
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="inputArea">
                <label for="password_confirmation">
                    Confirmar Senha
                </label>
                <input type="password" name="password_confirmation" placeholder="Confirme sua senha" required />
            </div>
            
            <div class="inputArea">
                <button type="submit" class="btn btn-primary">
                    Criar Conta
                </button>
            </div>
            
            <div class="auth-links">
                <p>Já tem uma conta? <a href="{{ route('login') }}">Faça login aqui</a></p>
            </div>
        </form>
    </section>
</x-layout>