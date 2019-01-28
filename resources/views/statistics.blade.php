@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between text-primary">
                <div>Обратная связь</div>
                <div class="text-secondary">Общая статистика*</div>
                
            </div>
            <div class="card-body">
                <ul>
                    @foreach ($statistics as $statistic)
                        <li>{{$statistic->key}} - {{$statistic->value}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
         
@endsection
