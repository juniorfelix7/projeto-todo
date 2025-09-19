<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$page ?? 'ToDoApp'}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css"/>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="{{route('home')}}">
                <img src="/assets/images/logo2.png" alt="logo"/>
            </a>
        </div>
    
        <div class="content">
            <nav>
                {{$btn ?? null}}
            </nav>
            <main>
               {{$slot}}
            </main>    
        </div>
    </div>
</body>
</html>