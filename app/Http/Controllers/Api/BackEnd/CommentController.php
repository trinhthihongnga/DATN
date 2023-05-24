<?php

namespace App\Http\Controllers\Api\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Exceptions\FailException;
use App\Models\Post;

class CommentController extends Controller
{
    public function getComments(Request $request)
    {
        $comments = Comment::where('post_id', $request->id)
            ->where('parent_id', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($request->page_number ?? 6);

        return CommentResource::collection($comments);
    }

    public function getComment($id)
    {
        $comment = Comment::find($id);

        return CommentResource::make($comment);
    }

    public function updateComment($id, Request $request)
    {
        $account_id = auth('accounts')->user();
        $comment = Comment::where('id', $id)->where('account_id', $account_id)->first();

        if(!$comment) {
            throw new FailException('Không thể cập nhật bình luận');
        }

        $comment->update([
            'content' => $request->content,
            'is_change' => 1
        ]);

        return response()->json(200);
    }

    public function createComment(CommentRequest $request, $id)
    {
        $post = Post::where('id', $id)
            ->where('is_approved', 1)
            ->first();
        if(!$post) {
            throw new FailException('Không tìm thấy bài viết');
        }
        $comment = Comment::create([
            'content' => $request->content,
            'account_id' => auth('accounts')->user()->id,
            'post_id' => $id
        ]);
        
        return response()->json($comment);
    }
}
