<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Kiosk
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereUpdatedAt($value)
 * @property string $location
 * @property string $asset_tag
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $registered_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereAssetTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereRegisteredAt($value)
 */
	class Kiosk extends \Eloquent {}
}

