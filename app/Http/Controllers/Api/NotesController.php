<?php

namespace App\Http\Controllers\Api;

use \DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Note;
use App\User;
use App\Country;
use App\Category;
use App\Tag;
use App\Photo;
use App\Facades\Slack;

class NotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user_id = request('user_id');
        $category_id = request('category_id');
        $tag_id = request('tag_id');
        $country_id = request('country_id');
        $is_best = request('is_best');
        $keyword = request('keyword');
        $limit = request('limit', 10);
        $notes = Note::select('id', 'user_id', 'category_id', 'title', 'isBest', 'date')
            ->with([
                'user:id,name,avater_path',
                'category:id,name',
                'countries:countries.id,name,english_name',
                'tags:tags.id,name',
                'photos:id,note_id,path'
            ])
            ->when($user_id, function($q) use ($user_id) {
                return $q->where('notes.user_id', $user_id);
            })
            ->when($category_id, function ($q) use ($category_id) {
                return $q->where('notes.category_id', $category_id);
            })
            ->when($is_best, function($q) {
                return $q->where('notes.isBest', true);
            })
            ->when($country_id, function($q) use ($country_id) {
                $note_ids = DB::table('country_note')->select('note_id')->where('country_id', $country_id)->pluck('note_id');
                return $q->whereIn('notes.id', $note_ids);
            })
            ->when($tag_id, function($q) use ($tag_id) {
                $note_ids = DB::table('note_tag')->select('note_id')->where('tag_id', $tag_id)->pluck('note_id');
                return $q->whereIn('notes.id', $note_ids);
            })
            ->when($keyword, function($q) use ($keyword) {
                $keyword = mb_convert_kana($keyword, 'as');
                $keyword_list = preg_split('/[\s,]+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
                foreach($keyword_list as $key) {
                    $q->where(function($q) use ($key) {
                        if(Country::where('name', $key)->exists()) {
                            $country_id = Country::where('name', $key)->first()->id;
                            $note_ids = DB::table('country_note')->select('note_id')->where('country_id', $country_id)->pluck('note_id');
                            $q->whereIn('notes.id', $note_ids);
                        }
                        if(User::where('name', 'LIKE', "$key %")->orWhere('name', 'LIKE', "% $key")->exists()) {
                            $user_ids = DB::table('users')->select('id')->where('name', 'LIKE', "$key %")->orWhere('name', 'LIKE', "% $key")->pluck('id');
                            $q->whereIn('notes.user_id', $user_ids);
                        }
                        $q->orWhere('notes.content', 'LIKE', "%$key%");
                        $q->orWhere('notes.title', 'LIKE', "%$key%");
                        return $q;
                    });
                }
            })
            ->withCount(['favUsers'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($limit);

        $conditions = [];
        if ($keyword) {
            $conditions[] = [
                "icon" => "mdi-text-search",
                "text" => $keyword,
            ];
        }
        if ($user_id) {
            $conditions[] = [
                "icon" => "mdi-account-edit",
                "text" => User::select('id','name')->where('id', $user_id)->first()->name
            ];
        }
        if ($country_id) {
            $conditions[] = [
                "icon" => "mdi-earth",
                "text" => Country::where('id', $country_id)->first()->name
            ];
        }
        if ($is_best) {
            $conditions[] = [
                "icon" => "mdi-star",
                "text" => "Best Note"
            ];
        }
        if ($category_id) {
            $conditions[] = [
                "icon" => "mdi-folder-outline",
                "text" => Category::where('id', $category_id)->first()->name
            ];
        }
        if ($tag_id) {
            $conditions[] = [
                "icon" => "mdi-tag",
                "text" => Tag::where('id', $tag_id)->first()->name
            ];
        }

        return collect(compact('conditions'))->merge($notes);
    }

    public function fav($noteId) {
        return auth()->user()->favNotes()->toggle([$noteId]);
    }

    public function get($noteId)
    {
        $note = Note::where('id', $noteId)
            ->select('id', 'user_id', 'category_id', 'title', 'isBest', 'date', 'content')
            ->with([
                'user:id,name,avater_path',
                'category:id,name',
                'countries:countries.id,name,english_name',
                'tags:tags.id,name',
                'photos:id,note_id,path'
            ])
            ->withCount(['favUsers'])
            ->firstOrFail();
        if (!request('for_edit', false)) {
            $escapedString = nl2br(htmlspecialchars($note->content, ENT_QUOTES, 'UTF-8'));
            $escapedString = str_replace('&amp;nbsp;', ' ', $escapedString);
            $pattern = '/(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:[\.\/][\?%#A-Z0-9][\?&%;=#A-Z0-9_-]*)+):?(\d+)?\/?/i';
            $replacement = '<a class="escaped_link" href="$0" target="_blank">$0</a>';
            $note->content = preg_replace($pattern, $replacement, $escapedString);
        }
        return $note;
    }

    public function store()
    {
        $note = Note::create(
            request(['title', 'user_id', 'date', 'category_id', 'isBest', 'content'])
        );

        $countryIds = [];
        foreach (request('countries', []) as $countryName) {
            $country = Country::firstOrCreate([
                'name' => $countryName
            ]);
            $countryIds[] = $country->id;
        }
        $note->countries()->sync($countryIds);

        $tagIds = [];
        foreach (request('tags', []) as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName
            ]);
            $tagIds[] = $tag->id;
        }
        $note->tags()->sync($tagIds);

        if (request()->file('files') !== null) {
            foreach (request()->file('files') as $i => $file) {
                $photo = \Image::make($file)->orientate();
                $photo = $this->resize($photo);
                $uniqid = uniqid();
                $ext = $file->guessExtension();
                $filename = "photo_{$note->id}_{$i}_{$uniqid}.{$ext}";
                $path = public_path('storage/img/note/') . $filename;
                $photo->save($path);
                $note->photos()->create(['path' => '/storage/img/note/'.$filename]);
            }
        }

        if (request('should_post_slack') && $note->user_id == auth()->user()->id) {
            Slack::postNote($note);
        }

        return $note;
    }

    public function update(Note $note)
    {
        $note->update(request(['title', 'user_id', 'date', 'category_id', 'isBest', 'content']));
        $countryIds = [];
        foreach (request('countries', []) as $countryName) {
            $country = Country::firstOrCreate([
                'name' => $countryName
            ]);
            $countryIds[] = $country->id;
        }
        $note->countries()->sync($countryIds);
        $tagIds = [];
        foreach (request('tags', []) as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName
            ]);
            $tagIds[] = $tag->id;
        }
        $note->tags()->sync($tagIds);

        if (request()->file('files') !== null) {
            foreach (request()->file('files') as $i => $file) {
                $photo = \Image::make($file)->orientate();
                $photo = $this->resize($photo);
                $uniqid = uniqid();
                $ext = $file->guessExtension();
                $filename = "photo_{$note->id}_{$i}_{$uniqid}.{$ext}";
                $path = public_path('storage/img/note/') . $filename;
                $photo->save($path);
                $note->photos()->create(['path' => '/storage/img/note/'.$filename]);
            }
        }

        foreach (request('delete_photo_ids', []) as $photoId) {
            Photo::find($photoId)->delete();
        }

        return $this->get($note->id);
    }

    public function destroy(Note $note)
    {
        foreach ($note->photos as $photo) {
            unlink(public_path($photo->path));
        }
        return response()->json(["ok" => $note->delete()]);
    }

    private function resize($photo)
    {
        $w = $photo->width();
        $h = $photo->height();
        if ($h > 800 && $h > $w) {
            $photo->resize(null, 800, function($constraint) { $constraint->aspectRatio(); });
        } elseif ($w > 800 && $w > $h) {
            $photo->resize(800, null, function($constraint) { $constraint->aspectRatio(); });
        }
        return $photo;
    }

    public function categories()
    {
        return Category::select('id', 'name')->get();
    }

    public function tags()
    {
        return Tag::select('id', 'name')->get();
    }
}
