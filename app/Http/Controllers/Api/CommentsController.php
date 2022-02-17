<?php

namespace App\Http\Controllers\Api;

use App\Requester;
use App\Ticket;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class CommentsController extends ApiController
{
    public function store(Ticket $ticket)
    {
        App::setLocale(request('language'));

        $comment = $ticket->addComment(null, strip_tags(request('body')), request('new_status'));

        if (request('new_status') == $ticket::STATUS_SOLVED) {
            return $this->respond(['id' => null, 'message' => __('validation.solvedTicket')], Response::HTTP_CREATED);
        }

        if (! $comment) {
            return $this->respond(['id' => null, 'message' => __('validation.emptyBodyComment')], Response::HTTP_BAD_REQUEST);
        }

        return $this->respond(['id' => $comment->id, 'message' => __('validation.commentCreated')], Response::HTTP_CREATED);
    }
}
