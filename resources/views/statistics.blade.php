@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <form class="d-flex" action={{ route('statistics.show')}} method="get">
                <input class="form-control" type="date" name="from" value="{{ old('from') ? old('from') : '' }}">
                <input class="form-control" type="date" name="to" value="{{ old('to') ? old('to') : '' }}">
                <input class="btn btn-primary" type="submit" value="Искать">
            </form>
        </div>
        <div class="card mb-4">
            <div class="card-header text-primary">
                <div class="text-primary">Обратная связь</div>
                <div class="text-primary">Общая статистика</div>
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
