<?php

namespace App;

use App\Http\Requests\HelpTopicForContextRequest;
use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    protected $fillable = ['content'];

    public static function getByRequestUrl(HelpTopicForContextRequest $request)
    {
        $normalizedContext = preg_replace('/[0-9]+/', '#', $request->input('context'));

        return self::whereContext($normalizedContext)->firstOrFail();
    }
}
