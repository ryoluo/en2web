<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Country;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function index()
    {
        return User::select('id', 'name', 'avater_path', 'generation', 'group_id', 'isOB', 'department_id')
            ->whereIn('status', [1,3])
            ->get();
    }
    public function get($id)
    {
        $user = User::select(
            'id', 
            'name', 
            'avater_path', 
            'coverimg_path', 
            'generation',
            'group_id',
            'isOB',
            'isOverseas',
            'isHennyu',
            'department',
            'major',
            'profile',
            'year',
            'job',
            'twitter_id',
            'instagram_id',
            'university',
            'status'
        )
            ->with('countries:countries.id,countries.name,english_name')
            ->with(['notes' => function($q) {
                $q
                ->select('id', 'user_id', 'category_id', 'title', 'isBest', 'date')
                ->with([
                    'user:id,name,avater_path',
                    'category:id,name',
                    'countries:countries.id,name,english_name',
                    'tags:tags.id,name',
                    'photos:id,note_id,path'
                ])
                ->withCount(['favUsers'])
                ->limit(4)
                ->orderBy('date', 'desc');
            }])
            ->withCount(['notes'])
            ->where('id', $id)
            ->first();
        if (!$user) {
            abort(404, 'not found');
        }
        $user->escaped_profile = $user->getEscapedProfile();
        return $user;
    }

    public function update()
    {
        $user = auth()->user();
        $user->update(
            request(['twitter_id', 'instagram_id', 'university', 'job', 'isOverseas', 'isOB', 'profile'])
        );

        $countryIds = [];
        foreach (request('countries', []) as $countryName) {
            $country = Country::firstOrCreate([
                'name' => $countryName
            ]);
            $countryIds[] = $country->id;
        }
        $user->countries()->sync($countryIds);

        return response()->json($user);
    }

    public function upload()
    {
        $type = request('type');
        $file = request()->file('file');
        $image = \Image::make($file)->orientate();
        if ($type === 'icon') {
            $image = $this->resizeIcon($image);
        } else {
            $image = $this->resizeCover($image);
        }
        $filename = uniqid('image_') . '.' . $file->guessExtension();
        $path = public_path('storage/img/tmp/') . $filename;
        $image->save($path);

        return response()->json([
            'path' => '/storage/img/tmp/' . $filename
        ]);
    }

    public function saveIcon()
    {
        $user = auth()->user();
        $tempPath = request('path');
        $filename = uniqid("avatar_{$user->id}_") . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
        Storage::disk('public')->move($tempPath, "/storage/img/user/{$filename}");
        if ($user->avater_path !== null) {
            unlink(public_path($user->avater_path));
        }
        $user->update(['avater_path' => "/storage/img/user/{$filename}"]);

        return $user;
    }

    public function saveCover()
    {
        $user = auth()->user();
        $tempPath = request('path');
        $filename = uniqid("cover_{$user->id}_") . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
        Storage::disk('public')->move($tempPath, "/storage/img/user/{$filename}");
        if ($user->coverimg_path !== null) {
            unlink(public_path($user->coverimg_path));
        }
        $user->update(['coverimg_path' => "/storage/img/user/{$filename}"]);

        return $user;
    }

    private function resizeIcon($image)
    {
        $w = $image->width();
        $h = $image->height();
        if ($h > 250 && $h > $w) {
            $image->resize(null, 250, function($constraint) { $constraint->aspectRatio(); });
        } elseif ($w > 250 && $w > $h) {
            $image->resize(250, null, function($constraint) { $constraint->aspectRatio(); });
        }
        return $image;
    }

    private function resizeCover($image)
    {
        $w = $image->width();
        $h = $image->height();
        if ($h > 500) {
            $image->resize(null, 500, function($constraint) { $constraint->aspectRatio(); });
        }
        return $image;
    }
}