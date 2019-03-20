<?php

namespace App;

use App\Http\Requests\HelpTopicForContextRequest;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HelpTopic extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['content'];

    public static function getByRequestUrl(HelpTopicForContextRequest $request)
    {
        $normalizedContext = preg_replace('/[0-9]+/', '#', $request->input('context'));

        return self::whereContext($normalizedContext)->firstOrFail();
    }
}
