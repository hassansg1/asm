<?php

namespace App\Http\Traits;

use App\Models\Parentable;
use ReflectionClass;

trait ParentTrait
{
    /**
     * @param $request
     * @param $item
     */
    function updateParent($request, $item)
    {
        if (isset($request->parent)) {
            $prt = explode('??', $request->parent);
            $item->parentable_type = $prt[0] ?? '';
            $item->parentable_id = $prt[1] ?? '';
            Parentable::addNew($prt[0] ?? null, $prt[1] ?? null, self::class, $item->id);
        }
    }

    /**
     * @param $item
     * @return mixed
     */
    public function parent($item = null)
    {
        $item = $item ?? $this;
        $par = Parentable::where([
            'childable_type' => get_class($item),
            'childable_id' => $item->id,
        ])->first();

        return $par;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function parentModel($item = null)
    {
        $par = self::parent($this);
        if ($par->parentable_type)
            return $par->parentable_type::find($par->parentable_id);

        return null;
    }

    public function getLink()
    {
        $reflect = new ReflectionClass($this);
        return route(strtolower($reflect->getShortName()) . '.index');
    }

    /**
     * @return string
     */
    public function parentName()
    {
        $par = self::parent($this);
        if ($par) {
            if ($par->parentable_type) {
                $parent = $par->parentable_type::find($par->parentable_id);
                return $parent->show_name ?? '';
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getParentableTypeAttribute()
    {
        $par = self::parentModel($this);
        if ($par) return get_class($par);
        return '';
    }

    /**
     * @return string
     */
    public function getParentCombineAttribute()
    {
        $par = self::parentModel($this);
        return get_class($par) . "??" . $par->id;
    }

    /**
     * @return string
     */
    public function getPermissionStringAttribute()
    {
        $par = self::parentModel($this);
        return $this->getTable() . get_class($par) . $par->id;
    }

    /**
     * @return string
     */
    public function getTableAttribute()
    {
        return $this->getTable();
    }

    /**
     * @return string
     */
    public function getCombineAttribute()
    {
        return get_class($this) . "??" . $this->id;
    }

    /**
     * @return string
     */
    public function getParentableIdAttribute()
    {
        $par = self::parentModel($this);
        if ($par) return $par->id;
        return '';
    }
}
