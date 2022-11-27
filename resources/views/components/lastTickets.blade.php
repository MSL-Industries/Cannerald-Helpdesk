@if($ticket->requester->tickets()->latest()->where('id', '!=', $ticket->id)->count())
    <div class="description">
    Last 5 Tickets:
        @foreach($ticket->requester->tickets()->latest()->where('id', '!=', $ticket->id)->limit(5)->get() as $otherTicket)
            <div>
                <span class="label ticket-status-{{ $otherTicket->statusName() }}">{{ __("ticket." . $otherTicket->statusName() ) }}</span> &nbsp;
                <span class="label ticket-priority-{{ $otherTicket->priorityName() }}">{{ __("ticket." . $otherTicket->priorityName() ) }}</span> &nbsp;
                <span class="label" style="background-color:{{$otherTicket->type->color ?? "white"}}"> {{ $otherTicket->type->name ?? "--" }}</span>

                <a style="margin-right: 5px;" href="/tickets/{{ $otherTicket->id }}"><i class="fa fa-eye"></i></a>
                <a style="margin-right: 5px;" href="/tickets/{{ $otherTicket->id }}" target="_blank"><i class="fa fa-external-link"></i></a>

                #{{ $otherTicket->id }} | {{ $otherTicket->title }}
            </div>
        @endforeach
    </div>
@endif