<div class="task">
            <div class="title">
                <input type="checkbox"
                    @if($data && $data['is_done'])
                        checked
                    @endif 
                    data-task-id="{{$data['id'] ?? ''}}"
                    onchange="toggleTaskStatus(this)"
                />       
                <div class="task_title">{{$data['title'] ?? ''}}</div>
            </div>
        <div class="priority">
            <div class="sphere"></div>
                <div>{{$data['category']->title ?? ''}}</div>
        </div>
        <div class="actions">
            <a href="{{route('task.edit', ['id' => $data['id']])}}">
                <img src="/assets/images/icon-edit.png" alt="Editar"/>    
            </a>
            <a href="{{route('task.delete', ['id' => $data['id']])}}" onclick="return confirmDelete('{{ $data['title'] }}')">
                <img src="/assets/images/icon-delete.png" alt="Excluir"/>    
            </a>
        </div>    
</div>