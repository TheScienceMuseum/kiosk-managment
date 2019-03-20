<?php

namespace App;

use App\Http\Requests\HelpTopicForContextRequest;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\HelpTopic
 *
 * @property int $id
 * @property string $context
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HelpTopic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
