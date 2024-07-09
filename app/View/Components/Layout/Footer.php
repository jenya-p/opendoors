<?php

namespace App\View\Components\Layout;

use App\Models\Content\Widget;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.footer', Widget::findByKey('footer')->translate()->data);
    }
}
