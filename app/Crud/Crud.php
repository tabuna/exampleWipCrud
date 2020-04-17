<?php

namespace App\Crud;

use App\Orchid\Screens\CrudEditScreen;
use App\Orchid\Screens\CrudListScreen;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Menu;
use Orchid\Support\Facades\Dashboard;

class Crud
{
    /**
     * The registered resource names.
     *
     * @var Collection
     */
    public $resources;

    /**
     * Crud constructor.
     */
    public function __construct()
    {
        $this->resources = collect();
    }

    /**
     * Register the given resources.
     *
     * @param string[] $resources
     *
     * @return Crud
     */
    public function resources(array $resources): Crud
    {
        $this->resources = $this->resources
            ->merge($resources)
            ->map(function (string $name) {
                return is_string($name) ? app($name) : $name;
            });

        return $this;
    }

    /**
     * Registers all the resources
     */
    public function boot(): void
    {
        $this->resources->each(function (Resource $resource) {
            $this->register($resource);
        });
    }

    /**
     * @param string $key
     *
     * @return Resource|null
     */
    public function find(string $key): ?Resource
    {
        return $this->resources->filter(function (Resource $resource) use ($key) {
            return $resource::uriKey() === $key;
        })->first();
    }

    /**
     * @param Resource $resource
     *
     * @return Crud
     */
    private function register(Resource $resource)
    {
        return $this
            ->registerRoute($resource)
            ->registerBreadcrumb($resource)
            ->registerMenu($resource)
            ->registerPermission($resource);
    }

    /**
     * @param Resource $resource
     *
     * @return Crud
     */
    private function registerRoute(Resource $resource): Crud
    {
        Route::domain((string)config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(function ($route) use($resource) {
                $route->screen('/crud/{resource?}/{id}', CrudEditScreen::class)
                    ->name("{$resource::uriKey()}.edit");

                $route->screen('/crud/{resource?}', CrudListScreen::class)
                    ->name("{$resource::uriKey()}.list");
            });

        return $this;
    }

    /**
     * @param Resource $resource
     *
     * @return Crud
     */
    private function registerMenu(Resource $resource): Crud
    {
        View::composer('platform::dashboard', function () use ($resource) {
            Dashboard::menu()->add(Menu::MAIN,
                ItemMenu::label($resource::label())
                    ->route("platform.{$resource::uriKey()}.list", [$resource::uriKey()])
                    ->sort(2000)
            );
        });

        return $this;
    }

    /**
     * @param Resource $resource
     *
     * @return Crud
     */
    private function registerPermission(Resource $resource): Crud
    {
        Dashboard::registerPermissions(
            ItemPermission::group('CRUD')
                ->addPermission($resource::uriKey(), $resource::label())
        );

        return $this;
    }

    /**
     * @param Resource $resource
     *
     * @return Crud
     */
    private function registerBreadcrumb(Resource $resource): Crud
    {
        Breadcrumbs::for("platform.{$resource::uriKey()}.list", function ($trail) {
            $trail->parent('platform.index');
            $trail->push('List');
        });

        Breadcrumbs::for("platform.{$resource::uriKey()}.edit", function ($trail)  use ($resource) {
            $trail->parent("platform.{$resource::uriKey()}.list");
            $trail->push('Edit');
        });

        return $this;
    }
}
