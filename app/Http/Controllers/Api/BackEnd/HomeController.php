<?php

namespace App\Http\Controllers\Api\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportAccountRequest;
use App\Http\Requests\ReportPostRequest;
use App\Http\Resources\PostResource;
use App\Models\Account;
use App\Models\AccountSavePost;
use App\Models\Post;
use App\Models\ReportAccount;
use App\Models\ReportPost;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

class HomeController extends Controller
{
    public function getPost($slugPost)
    {
        $post = Post::where('slug', $slugPost)
            ->where('is_approved', 1)
            ->first();

        if(!$post) {
            abort(404);
        }

        $post->count_view++;
        $post->save();

        return PostResource::make($post);
    }
}
