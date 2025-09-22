<x-layout page="Meu Perfil">

    <style>
        .profile-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            background-color: var(--color-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
        }
        
        .profile-name {
            font-size: 28px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .profile-email {
            font-size: 18px;
            color: #666;
        }
    </style>

    <div class="profile-container">
        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <h1 class="profile-name">{{ $user->name }}</h1>
        <p class="profile-email">{{ $user->email }}</p>

        <div class="profile-btns">
        <style>
            .profile-btns {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px; /* Espaçamento entre os botões */
                margin-top: 20px; /* Espaçamento do conteúdo do perfil */
            }

            .profile-btns button,
            .profile-btns a {
                width: 200px; /* Largura fixa para os botões */
                text-align: center; /* Garante que o texto fique centralizado */
            }
        </style>

     <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Sair
            </button>
     </form>
     <a href="{{ route('password.request') }}" class="btn btn-primary">Trocar Senha</a>

    </div>
    </div>
    
    
    

</x-layout>
