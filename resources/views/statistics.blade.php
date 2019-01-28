@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <form action={{ route('statistics.show')}} method="get">
                <input type="date" name="from">
                <input type="date" name="to">
                <input type="submit">
            </form>
        </div>
        <div class="card mb-4">
            <div class="card-header   text-primary">
                <div class="text-primary">Обратная связь</div>
                <div class="text-primary">Общая статистика*</div>
            </div>
            <div class="card-body">
                <ul>
                    @foreach ($statistics as $statistic)
                        <li>{{$statistic["key"]}}-{{$statistic["value"]}}</li> 
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
         
@endsection
