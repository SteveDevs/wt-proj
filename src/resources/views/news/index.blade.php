<h1>Top Stories</h1>
@foreach($data['top'] as $top)
    <h3>Title: {{ $top['title'] }} Time: {{ $top['time'] }}</h3>
    <p>Comments: </p>
    @foreach($top->comments as $comment)
        <p>{{$comment->by}} {{\Carbon\Carbon::createFromTimestamp($comment->time)->toDateTimeString()}}</p>
        <p>{{$comment->text}}</p>
    @endforeach
@endforeach

<h1>New Stories</h1>
@foreach($data['new'] as $top)
    <h3>Title: {{ $top->title }} Time: {{ $top->time }}</h3>
    <p>Comments: </p>
    @foreach($top->comments as $comment)
        <p>{{$comment->by}} {{\Carbon\Carbon::createFromTimestamp($comment->time)->toDateTimeString()}}</p>
        <p>{{$comment->text}}</p>
    @endforeach
@endforeach

<h1>Best Stories</h1>
@foreach($data['best'] as $top)
    <p>Title: {{ $top->title }} Time: {{ $top->time }}</p>
    <p>Comments: </p>
    @foreach($top->comments as $comment)
        <p>{{$comment->by}} {{\Carbon\Carbon::createFromTimestamp($comment->time)->toDateTimeString()}}</p>
        <p>{{$comment->text}}</p>
    @endforeach
@endforeach
