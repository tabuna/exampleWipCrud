<?php

namespace App\Orchid\Screens;

use App\Crud\Crud;
use App\Crud\Resource;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;

class CrudListScreen extends Screen
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
     * @param Crud   $crud
     *
     * @return array
     */
    public function query(string $resourceKey, Crud $crud): array
    {
        /** @var Resource $resource */
        $this->resource = $crud->find($resourceKey);

        abort_if($this->resource === null, 404);

        /** @var Model $model */
        $model = app($this->resource::$model);

        return [
            'model' => $model->filters()->paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        $grid = $this->resource->grid();
        $grid[] = TD::set('')
            ->align(TD::ALIGN_CENTER)
            ->render(function (Model $model) {
                return Link::make(__('Edit'))
                    ->route("platform.{$this->resource::uriKey()}.edit", [
                        $this->resource::uriKey(),
                        $model->getAttribute($model->getKeyName()),
                    ])
                    ->icon('icon-pencil');
            });


        return [
            Layout::table('model', $grid)
        ];
    }
}
