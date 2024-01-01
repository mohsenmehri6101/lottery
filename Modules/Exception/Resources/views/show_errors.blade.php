@extends('exception::layouts.master')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">id</th>
            <th scope="col">status_code</th>
            <th scope="col">url</th>
            <th scope="col">exception</th>
            <th scope="col">message</th>
            <th scope="col">user_creator</th>
            <th scope="col">stack_trace</th>
            <th scope="col">requests</th>
            <th scope="col">headers</th>
            <th scope="col">user_agent</th>
            <th scope="col">extra_date</th>
            <th scope="col">created_at</th>
            <th scope="col">updated_at</th>
            <th scope="col">deleted_at</th>
        </tr>
        </thead>
        <tbody>
        @foreach($exceptions as $exception)
            <tr>
                <td scope="col">#</td>
                <td scope="col">{{ $exception?->id ?? '-' }}</td>
                <td scope="col">{{ $exception?->status_code ?? '-' }}</td>
                <td scope="col">{{ $exception?->url ?? '-' }}</td>
                <td scope="col">{{ $exception?->exception ?? '-' }}</td>
                <td scope="col">{{ $exception?->message ?? '-' }}</td>
                <td scope="col">{{ $exception?->user_creator ?? '-' }}</td>
                <td scope="col">{{ $exception?->stack_trace ?? '-' }}</td>
                <td scope="col">{{ $exception?->requests ?? '-' }}</td>
                <td scope="col">{{ $exception?->headers ?? '-' }}</td>
                <td scope="col">{{ $exception?->user_agent ?? '-' }}</td>
                <td scope="col">{{ $exception?->extra_date ?? '-' }}</td>
                <td scope="col">{{ $exception?->created_at ?? '-' }}</td>
                <td scope="col">{{ $exception?->updated_at ?? '-' }}</td>
                <td scope="col">{{ $exception?->deleted_at ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {!! $exceptions->links('') !!}
        </div>
    </div>
@endsection
