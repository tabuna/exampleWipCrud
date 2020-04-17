<?php

namespace App\Orchid\Screens;

use App\Crud\Crud;
use App\Crud\Resource;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Action;
use Orchid\Screen\Field;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class CrudEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'CrudScreen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'CrudScreen';

    /**
     * @var Resource|null
     */
    private $resource;

    /**
     * Query data.
     *
     * @param string $resourceKey
     * @param string $primary
     * @param Crud   $crud
     *
     * @return array
     */
    public function query(string $resourceKey, string $primary, Crud $crud): array
    {
        /** @var Resource $resource */
        $this->resource = $crud->find($resourceKey);

        abort_if($this->resource === null, 404);

        /** @var Model $model */
        $model = app($this->resource::$model);

        return [
            'model' => $model->findOrFail($primary)
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [

        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        $fields = array_map(function (Field $field) {
            return $field->set('name', 'model.' . $field->get('name'));
        }, $this->resource->fields());

        return [
            Layout::rows($fields)
        ];
    }
}
