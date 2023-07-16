<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use App\Support\Discount\Validator\Contracts\AbstractDiscountVlidator;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
public $validator;
    public function __construct(AbstractDiscountVlidator $validator)
    {
        $this->middleware('auth:api', ['except' => ['index' , 'show' , 'upload','test']]);
        $this->validator = $validator;

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::paginate();
        return \response()->json(new ArticleCollection($articles), 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validation($request);
        Article::create([
            'user_id' => auth('api')->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image
        ]);
        return \response()->json([
            'message' => 'created'
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return \response()->json([
            'data' => new ArticleResource($article)
        ], 200);
        //handler error in Handler.php

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $article->update($request->all());
        return \response()->json([
           'message' => 'updated'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return \response()->json([
            'message' => 'deleted'
        ], 200);
    }

    private function validation(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required']
        ]);
    }

    private function imageUpload(Request $request)
    {
        return $request->hasFile('image')
            ? $request->image->store('public') : null;
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $year = Carbon::now()->year;
        $mediaUrl = "/upload/Product/videos/{$year}/";
        $fileName = $file->getClientOriginalName();
        $file = $file->move(public_path($mediaUrl), $fileName);
        $url = $mediaUrl . $fileName ;
        return $url;

    }

    public function test(Request $request)
    {
        $user = User::whereId(1)->first();
        dd($this->validator->validate($user));
    }
}
