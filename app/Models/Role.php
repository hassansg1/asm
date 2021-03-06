<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class Role extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];


    public $rules =
        [
            'name' => 'required | max:255',
        ];


    public function parentName()
    {
        return '';
    }

    /**
     * @param $request
     * @param $item
     */
    function updateLocations($request, $item)
    {
        UserLocation::where(['user_id' => $item->id])->delete();
        if (isset($request->location)) {
            foreach ($request->location as $location) {
                $prt = explode('??', $location);
                $item->parentable_type = $prt[0] ?? '';
                $item->parentable_id = $prt[1] ?? '';
                UserLocation::addNew($prt[0] ?? null, $prt[1] ?? null, $item->id);
            }
        }
    }

    /**
     * @param $request
     * @param $item
     */
    function updateAssetPermissions($request, $item)
    {
        $item->permissions()->delete();
        if (isset($request->permissions)) {
            foreach ($request->permissions as $entry) {
                Permission::updateOrCreate(["name" => $entry], ["name" => $entry, 'guard_name' => 'web', 'group' => $entry]);
                $item->givePermissionTo($entry);
            }
        }
    }


    /**
     * @param $item
     * @param $request
     * @return mixed
     */
    public function saveFormData($item, $request)
    {
        if (isset($request->name)) $item->name = $request->name;
        $item->guard_name = 'web';
        $item->save();
        $role = \Spatie\Permission\Models\Role::findByName($item->name);
        if (isset($request->permissions)) $role->syncPermissions($request->permissions);

        $this->updateLocations($request, $item);
        $this->updateAssetPermissions($request, $item);
        return $item;
    }
}
