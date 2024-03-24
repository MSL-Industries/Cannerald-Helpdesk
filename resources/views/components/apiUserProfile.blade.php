@if($user)
    <style>
        .user-infos > * {
            border: 1px solid black;
            color: black;
            padding: 2px;
            margin-top: 5px;
            display: inline-block;
        }
        .user-infos > a {
            border: 1px solid gray;
        }
    </style>

    <div class="user-infos description">
        <a target="_blank" href="{{ env('APP_ADMIN_PAGE') }}user/{{ $user->id  }}/show">Open</a>

        @if(isset($user->support->display))
            @foreach ($user->support->display as $label => $item)
                <span>{{$label}}: {{ $item }}</span>
            @endforeach
        @endif
    </div>
@else
    <div class="user-infos description">
        Kein Benutzerkonto gefunden
    </div>
@endif