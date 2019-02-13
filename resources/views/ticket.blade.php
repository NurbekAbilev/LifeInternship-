@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between text-primary">
                <div>{{ $ticket->full_name }}</div>
                <div class="text-secondary">{{ $ticket->created_at }}</div>
            </div>
            <div class="card-body container">
                <div class="row no-gutters d-flex justify-content-between">
                    <div class="col-md-8">
                        <ul>
                            <li>Email: {{$ticket->email}}</li>
                            <li>Номер: {{$ticket->phone_num}}</li>
                            <li>Категория: {{$ticket->category->name}}</li>
                            <li class="{{ $ticket->statusColor() }}">Статус: {{$ticket->status->name}}</li>
                            @if ($ticket->admin_id)
                                <li>Админ: {{ $ticket->admin->name }}</li>
                                <li>Время: {{ $ticket->answered_at }}</li>
                            @endif
                        </ul>
                    </div>

                    <div class="d-flex">
                        @if (Auth::check())
                            @if (Auth::user()->isAdmin())
                                <form class="mb-2 mr-2" method="POST" action="{{ route('ticket.process', ['hash' => $ticket->hash]) }}">
                                    @csrf

                                    <input class="btn btn-danger" type="submit" value="В обработке">
                                </form>
                            @endif

                            <form method="POST" action="{{ route('ticket.close', ['hash' => $ticket->hash]) }}">
                                @csrf

                                <input class="btn btn-secondary" type="submit" value="Закрыть">
                            </form>
                        @endif
                    </div>
                </div>
                <p>Описание:</p>
                <p>{{$ticket->description}}</p>
                @if ($ticket->file_path)
                    <a class="btn btn-primary" target="_blank" rel="noopener noreferrer" href={{ route('ticket.attachment', ['hash' => $ticket->hash]) }}>Прикрепленный файл</a>
                @endif
            </div>
        </div>

        @foreach ($ticket->comments as $comment)
            <div class="card mb-4 {{ $comment->admin_only ? 'border-secondary' : '' }}">
                <div class="card-header d-flex justify-content-between">
                    <div>{{ $comment->user->name }}</div>

                    <div class="text-secondary">{{ $comment->created_at }}</div>
                </div>

                <div class="card-body">{{ $comment->content }}</div>
            </div>
        @endforeach

        <div class="card mb-4">
            <div class="card-header">Написать комментарий</div>

            <div class="card-body">
                <form method="POST" action="/ticket/{{ $ticket->hash }}">
                    @csrf
                    
                    <textarea class="form-control mb-3 {{ $errors->has('content') ? 'is-invalid' : '' }}"
                        name="content" placeholder="Комментарий">{{ old('content') ? old('content') : '' }}</textarea>

                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-success">Написать</button>

                        @if (Auth::check() && Auth::user()->isAdmin())
                            <div class="form-group form-check mb-0 h-100">
                                <input type="checkbox" name="admin_only" class="form-check-input" id="check">

                                <label class="form-check-label" for="check">Только для админов</label>
                            </div>
                        @endif
                    </div>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger m-0 mt-3">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
