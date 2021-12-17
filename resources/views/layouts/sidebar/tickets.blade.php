<h4> @icon(inbox) {{ trans_choice('ticket.ticket', 2) }}</h4>
<ul>
    <li style="height: auto; max-height: none; display: list-item; margin-bottom: 20px;">
        @foreach (\App\TicketType::all() as $ticketType)
                <a style="display: inline-block" class="p1" href="/tickets?filters={{ base64_encode('App\ThrustHelpers\Filters\TicketTypeFilter=' . $ticketType->id) }}">
                    <span class="label" style="padding-right: 0; color: black;background-color:{{ $ticketType->color }}">{{$ticketType->name}} <span class="label" style="margin-right: 0">{{$ticketType->tickets()->where('status', '<', 4)->count()}}</span></span>
                </a>
        @endforeach
    </li>

    @php ( $repository = new App\Repositories\TicketsRepository )
    @if( auth()->user()->assistant )
        @include('components.sidebarItem', ["url" => route('tickets.index') . "?escalated=true",    "title" => __('ticket.escalated'),  "count" => $repository->escalated()     ->count()])
    @endif
    @include('components.sidebarItem', ["url" => route('tickets.index'),          "title" => 'Letzter Filter',       "count" => $repository->all()               ->count()])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?all=true",          "title" => __('ticket.open'),       "count" => $repository->all()               ->count()])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?unassigned=true",   "title" => __('ticket.unassigned'), "count" => $repository->unassigned()        ->count()])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?assigned=true",     "title" => __('ticket.myTickets'),  "count" => $repository->assignedToMe()      ->count()])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?recent=true",       "title" => __('ticket.recent'),     "count" => $repository->recentlyUpdated()   ->count()])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?solved=true",       "title" => __('ticket.solved')])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?closed=true",       "title" => __('ticket.closed')])
    @include('components.sidebarItem', ["url" => route('tickets.index') . "?rated=true",       "title" => __('ticket.rated')])
    @include('components.sidebarItem', ["url" => route('requesters.index') ,                    "title" => trans_choice('ticket.requester', 2)])
</ul>