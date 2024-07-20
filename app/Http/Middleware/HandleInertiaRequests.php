<?php

namespace App\Http\Middleware;

use App\Models\Backfeed;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware {


    protected $rootView = 'app';

    public function version(Request $request) {
        return $this->rootView . parent::version($request);
    }

    public function handle(Request $request, \Closure $next) {

        if ($request->routeIs('admin.*')) {
            $this->rootView = 'admin';

        } else {
            $this->rootView = 'app';
        }

        return parent::handle($request, $next);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        $ret = [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
        ];

        $ret['locale'] = app()->getLocale();

        if ($request->routeIs('admin.*')) {
            $ret['notifications'] = [

            ];
        }

        $ret['sidebar'] = $this->buildSidebar();

        return $ret;

    }

    private function buildSidebar() {
        if (!\Auth::user()) {
            return null;
        }

        $sidebar = [];
        if (\Auth::user()->can('manage-users')) {
            $sidebar[] =
                [
                    'key' => 'user',
                    'label' => 'Пользователи',
                    'i' => 'fa fa-users',
                    'items' => [
                        [
                            'key' => 'user',
                            'label' => 'Все пользователи',
                            'i' => 'fa fa-key',
                            'href' => route('admin.user.index')
                        ],
                        [
                            'key' => 'admin',
                            'label' => 'Администраторы',
                            'i' => 'fa fa-user-tie',
                            'href' => route('admin.admin.index')
                        ],
                        [
                            'key' => 'university-user',
                            'label' => 'Представители ВУЗ-ов',
                            'i' => 'fa fa-person-chalkboard',
                            'href' => route('admin.university-user.index')
                        ],
                        [
                            'key' => 'student',
                            'label' => 'Участники',
                            'i' => 'fa fa-users',
                            'href' => route('admin.student.index')
                        ]
                    ]

                ];
        }

        if (\Auth::user()->can('manage-site')) {
            $sidebar[] = [
                'key' => 'site',
                'label' => 'Сайт',
                'i' => 'fa fa-globe',
                'items' => [
                    [
                        'key' => 'widget',
                        'href' => route('admin.widget.index'),
                        'i' => "fa fa-icons",
                        'label' => "Виджеты",
                    ],

                    [
                        'key' => 'news',
                        'href' => route('admin.news.index'),
                        'i' => "fa fa-newspaper",
                        'label' => "Новости",
                    ],
                    [
                        'key' => 'schedule',
                        'href' => route('admin.schedule.index'),
                        'i' => "fa fa-calendar",
                        'label' => "График",
                    ],
                    [
                        'key' => 'faq',
                        'href' => route('admin.faq.index'),
                        'i' => "fa fa-question-circle",
                        'label' => "FAQ",
                    ],
                    [
                        'key' => 'profile-file-type',
                        'href' => route('admin.profile-file-type.index'),
                        'i' => "fa fa-folder-open",
                        'label' => "Типы файлов",
                    ],
                ]
            ];
        }

        // if (\Auth::user()->can('manage-quiz')) {
        $items = [];
        if (\Auth::user()->can('viewAny', Quiz::class)) {
            $items[] = [
                'key' => 'quiz',
                'href' => route('admin.quiz.index'),
                'i' => "fa fa-list-check",
                'label' => "Группы заданий",
            ];
        }

        if (\Auth::user()->can('viewAny', Question::class)) {
            $items[] = [
                'key' => 'quiz-question',
                'href' => route('admin.quiz-question.index'),
                'i' => "fa fa-list-check",
                'label' => "Задания",
            ];
        }

        if (\Auth::user()->can('manage-site')) {
            $items[] = [
                'key' => 'quiz-attempt',
                'i' => "fa fa-user-check",
                'label' => "Попытки",
            ];
            $items[] = [
                'key' => 'quiz-validation',
                'i' => "fa fa-check-double",
                'label' => "Проверка",
            ];
        }

        if (!empty($items)) {
            $sidebar[] = [
                'key' => 'quiz',
                'label' => 'Задания олимпиады',
                'i' => 'fa fa-spell-check',
                'items' => $items
            ];
        }


        if (\Auth::user()->can('manage-portfolio')) {
            $sidebar[] = [
                'key' => 'portfolio',
                'label' => 'Портфолио',
                'i' => 'fa fa-address-card',
                // 'items' => []
            ];
        }

        if (\Auth::user()->can('manage-interview')) {
            $sidebar[] =

                [
                    'key' => 'interview',
                    'label' => 'Собеседования',
                    'i' => 'fa-solid fa-comments',
                    'items' => [
                        [
                            'key' => 'slots',
                            'i' => "fa fa-clock",
                            'label' => "Слоты",
                        ],
                        [
                            'key' => 'meetup',
                            'i' => "fa-solid fa-people-arrows",
                            'label' => "Собеседования",
                        ],
                        [
                            'key' => 'interview',
                            'i' => "fa fa-chalkboard-teacher",
                            'label' => "Научные руководители",
                        ],
                        [
                            'key' => 'suppervisor',
                            'i' => "fa fa-users",
                            'label' => "Участники",
                        ],
                        [
                            'key' => 'my-interview',
                            'i' => "fa - solid fa - people - arrows",
                            'label' => "Мои собеседования",
                        ],
                        '' => [
                            'key' => 'supervisor-choosing',
                            'i' => "fa fa - chalkboard - teacher",
                            'label' => "Выбор научного руководителя",
                        ]
                    ],
                ];
        }

        if (count($sidebar) == 1 && !empty($sidebar[0]['items'])){
            return $sidebar[0]['items'];
        }

        return $sidebar;
    }

}
