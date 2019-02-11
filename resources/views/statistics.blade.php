@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <form class="card-body" method="GET" action={{ route('statistics.show') }}>
                <div class="form-row align-items-end">
                    <div class="form-group col-md-5 mb-0">
                        <label for="from">From:</label>
                        <input class="form-control" type="date" name="from" id="from" value="{{ old('from') ? old('from') : '' }}">
                    </div>
                    <div class="form-group col-md-5 mb-0">
                        <label for="to">To:</label>
                        <input class="form-control" type="date" name="to" id="to" value="{{ old('to') ? old('to') : '' }}">
                    </div>
                    <div class="form-group col-md-2 mb-0">
                        <input class="btn btn-primary w-100" type="submit" value="Искать">
                    </div>
                </div>
            </form>
        </div>
        <div class="card mb-4">
            <h5 class="card-header py-3">Общая статистика</h5>
            <div class="card-body">
                <table class="table table-bordered table-hover w-100 mb-0">
                    @foreach ($statistics as $statistic)
                        <tr>
                            <td>{{ $statistic["key"] }}</td>
                            <th scope="row">
                                <p class="text-center mb-0">{{ $statistic["value"] }}</p>
                            </th>
                        </tr> 
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
