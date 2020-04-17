<?php

namespace App\Resources;

use App\Crud\Resource;
use App\User;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;

class UserResource extends Resource
{
    /**
     * @var string
     */
    public static $model = User::class;

    /**
     * @return array
     */
    public function grid(): array
    {
        return [
            TD::set('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (\Orchid\Platform\Models\User $user) {
                    return$user->presenter()->title();
                }),

            TD::set('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::set('updated_at', __('Last edit'))
                ->sort()
                ->render(function (User $user) {
                    return $user->updated_at->toDateTimeString();
                }),
        ];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),
        ];
    }
}
