<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth; // For using loggedin user_id. Don't forget this!

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function store(Request $request, $post_id)
    {
        $request->validate([
            'comment_body' . $post_id => 'required|max:150'
        ],
        [
            'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
            'comment_body' . $post_id . '.max'      => 'The comment must not have more than 150 characters.'
        ]
    );

        $this->comment->body        = $request->input('comment_body' . $post_id); //what is the comment
        $this->comment->user_id     = Auth::user()->id; // Who made the comment
        $this->comment->post_id     = $post_id; // which post was commented
        $this->comment->save();

        return redirect()->route('post.show', $post_id);
    }

    # Delete Comment
        ### Create destroy() in the CommentConttroller.
        ### add a new route.
        ### Use the route.
        
    public function destroy($id)
    {
        $this->comment->destroy($id);

        return redirect()->back();
    }
}

