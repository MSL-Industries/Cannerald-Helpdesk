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
        <span>Verification Level: {{ $user->verification_level }}</span>
        <span>Plants: {{ $user->plants->level_1 }}/{{ $user->plants->level_2 }}/{{ $user->plants->level_3 }}</span>
        <span>Angemeldet seit: {{ $user->created_at }}</span>
        <span>Firstliner: {{ $user->firstline }}</span>
        <span>Language: {{ $user->language }}</span>
    </div>
@else
    <div class="user-infos description">
        Kein Benutzerkonto gefunden
    </div>
@endif