<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Attachment;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use App\Policies\Quiz\QuestionPolicy;
use App\Policies\Quiz\QuizPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Blade::directive('translate', function (string $expression) {
            return '<?php if(' . $expression . ' instanceof \Illuminate\Database\Eloquent\Model && method_exists(' . $expression . ', \'translate\')){
                ' . $expression . '->translate();
            } else if (' . $expression . ' instanceof \Illuminate\Support\Collection){
                foreach(' . $expression . ' as $__item){
                     if($__item instanceof \Illuminate\Database\Eloquent\Model && method_exists(' . $expression . ', \'translate\')){
                        $__item->translate();
                    }
                }
            } ?>';
        });

        Collection::macro('translate', function(){
            $this->each(function(Model $model){
                if(method_exists($model, 'translate')){
                    $model->translate();
                }
            });
            return $this;
        });

        Arr::macro('assocToOptions', function ($src) {
            $options = [];
            foreach ($src as $id => $name) {
                $options[] = ['id' => $id, 'name' => $name];
            }
            return $options;
        });

        Arr::macro('iif', function ($src, $key, $empty = null) {
            if(array_key_exists($key, $src)){
                return $src[$key];
            } else {
                if(empty($key) && !empty($empty)){
                    return $empty;
                } else {
                    return $key;
                }
            }
        });

        Relation::morphMap(Attachment::ITEM_CLASSES);

        Gate::define('admin', function (User $user) {
            return !empty($user->admin);
        });

        Gate::define('admin-users', function (User $user) {
            return $user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_USERS);
        });

        Gate::define('admin-site', function (User $user) {
            return $user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_SITE);
        });

        Gate::define('admin-dirs', function (User $user) {
            return $user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_DIRS);
        });


        Gate::define('admin-quiz', function (User $user) {
            return $user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_QUIZZES);
        });

        Gate::define('admin-interview', function (User $user) {
            return $user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_INTERVIEW);
        });

        Gate::define('admin-portfolio', function (User $user) {
            return $user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_PORTFOLIOS);
        });

        Gate::define('participant', function (User $user) {
            return $user->participant()->exists();
        });

        Gate::policy(Question::class, QuestionPolicy::class);
        Gate::policy(Quiz::class, QuizPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

    }
}
