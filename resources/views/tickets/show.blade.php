@extends('layouts.app')
@section('content')
    <div style="display: flex; width: 100%;">
        <div style="width: 60%">
            @if( $ticket->canBeEdited() )

                <div class="comment new-comment">
                    {{ Form::open(["url" => route("comments.store", $ticket) , "files" => true, "id" => "comment-form"]) }}

                    @if($user)
                    <textarea id="comment-text-area" name="body"
                              style="height:200px; width: 100%; resize: vertical;">{{ $user->language == 'de' ? 'Hallo' : 'Hello' }} {{ $user->firstname }},&#13;@if(auth()->user()->settings->tickets_signature)&#13;&#13;&#13;{{ auth()->user()->settings->tickets_signature }}@endif
                    </textarea>
                    @else
                    <textarea id="comment-text-area" name="body"
                          style="height:200px; width: 100%; resize: vertical;">Hello {{$ticket->requester->name}},&#13;@if(auth()->user()->settings->tickets_signature)&#13;&#13;&#13;{{ auth()->user()->settings->tickets_signature }}@endif
                    </textarea>
                    @endif

                    @include('components.uploadAttachment', ["attachable" => $ticket, "type" => "tickets"])
                    {{ Form::hidden('new_status', $ticket->status, ["id" => "new_status"]) }}
                    @if($ticket->isEscalated() )
                        <button class="mt1 uppercase ph3"> @icon(comment) {{ __('ticket.note') }} </button>
                    @else
                        <div class="mb1">
                            {{ __('ticket.note') }}: {{ Form::checkbox('private') }}
                        </div>
                        <button class="mt1 uppercase ph3">
                            @icon(comment) {{ __('ticket.commentAs') }} {{ $ticket->statusName() }}</button>
                        <span class="dropdown button caret-down"> @icon(caret-down) </span>
                        <ul class="dropdown-container">
                            <li><a class="pointer" onClick="setStatusAndSubmit( {{ App\Ticket::STATUS_OPEN    }} )">
                                    <div style="width:10px; height:10px"
                                         class="circle inline ticket-status-open mr1"></div> {{ __('ticket.commentAs') }}
                                    <b>{{ __("ticket.open") }}   </b> </a></li>
                            <li><a class="pointer" onClick="setStatusAndSubmit( {{ App\Ticket::STATUS_PENDING }} )">
                                    <div style="width:10px; height:10px"
                                         class="circle inline ticket-status-pending mr1"></div> {{ __('ticket.commentAs') }}
                                    <b>{{ __("ticket.pending") }}</b> </a></li>
                            <li><a class="pointer" onClick="setStatusAndSubmit( {{ App\Ticket::STATUS_SOLVED  }} )">
                                    <div style="width:10px; height:10px"
                                         class="circle inline ticket-status-solved mr1"></div> {{ __('ticket.commentAs') }}
                                    <b>{{ __("ticket.solved") }} </b> </a></li>
                        </ul>
                    @endif
                    {{ Form::close() }}
                </div>
            @endif

            @include('components.ticketComments', ["comments" => $ticket->commentsAndNotesAndEvents()->sortBy('created_at')->reverse() ])

        </div>
        <div style="width: 40%; border-left: solid 2px gray">
            <div style="position: sticky; top: 0;">
                <div class="description comment">
                    <div class="breadcrumb">
                        <a href="{{ route('tickets.index') }}">{{ trans_choice('ticket.ticket', 2) }}</a>
                    </div>
                    <h3>#{{ $ticket->id }}. {{ $ticket->title }} </h3>
                    <div class="mb2">
                        @include('components.ticket.rating')
                    </div>

                    @include('components.ticket.header')
                    @include('components.ticket.merged')
                </div>

                <hr/>
                @include('components.apiUserProfile', ['ticket' => $ticket])
                <hr/>
                @include('components.lastTickets', ['ticket' => $ticket])
                <hr/>
                @include('components.assignActions', ["endpoint" => "tickets", "object" => $ticket])
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    @include('components.js.taggableInput', ["el" => "tags", "endpoint" => "tickets", "object" => $ticket])

    <script>
        function setStatusAndSubmit(new_status) {
            $("#new_status").val(new_status);
            $("#comment-form").submit();
        }
    </script>
@endsection