<?php

namespace App\Crud;

use App\User;
use Illuminate\Support\Str;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\TD;

abstract class Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = '';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = '';

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->{static::$title};
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return Str::plural(class_basename(get_called_class()));
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return TD[]
     */
    abstract public function grid(): array;

    /**
     * Get the fields displayed by the resource.
     *
     * @return Field[]
     */
    abstract public function fields(): array;

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return Str::plural(Str::kebab(class_basename(get_called_class())));
    }
}
