<?php

namespace App\Console\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class EntityMakeCommand extends Command
{
    protected $signature = 'make:app-entity';

    public function __construct() {
        parent::__construct();
        $this->addOption('force');
    }

    public function handle()
    {



        $this->entity(
            'region', [
            'Name' => 'Region',
            'titles' => 'Регионы',
            'title_new' => 'Новый регион',
            'table' => 'dir_regions',
            'Namespace' => 'Dir',
            'route' => 'dir-region'
        ]);


        $this->entity(
            'country', [
            'Name' => 'Country',
            'titles' => 'Страны',
            'title_new' => 'Новая страна',
            'table' => 'dir_countries',
            'Namespace' => 'Dir',
            'route' => 'dir-country'
        ]);


        $this->entity(
            'citizenship', [
            'Name' => 'Citizenship',
            'Namespace' => 'Dir',
            'titles' => 'Гражданство',
            'title_new' => 'Новое',
            'table' => 'dir_citizenships',
            'route' => 'dir-citizenship'
        ]);



    }


    protected function entity($name, $params = []){
        $params['name'] = $name;
        if(empty($params['table'])){
            $params['table'] = Str::plural($name);
        }
        if(empty($params['Name'])){
            $params['Name'] = Str::ucfirst($name);
        }
        if(empty($params['sc_name'])){
            $params['sc_name'] = Str::snake($name);
        }
        if(empty($params['route'])){
            $params['route'] = Str::snake($name,'-');
        }
        if(empty($params['Namespace'])){
            $params['Namespace'] = '';
            $namespace = '';
        } else {
            $params['Namespace'] = '\\' . $params['Namespace'];
            $namespace = $params['Namespace'];
        }
        $params['NamespacePath'] = str_replace('\\', '/', $namespace);

        if(empty($params['titles'])){
            $params['titles'] = Str::plural($params['Name']);
        }

        if(empty($params['title_new'])){
            $params['title_new'] = "Новый " . $params['Name'];
        }

//        $this->one('Model.php', 'app/Models' . $namespace. '/' . $params['Name'] . '.php', $params);
        $this->one('AdminSimpleController.php', 'app/Http/Controllers/Admin' . $namespace . '/' . $params['Name'] . 'Controller.php', $params);
//        $this->one('Edit.vue', 'resources/js/Pages/Admin' . $namespace . '/' . $params['Name'] . '/Edit.vue', $params);
//        $this->one('SimpleIndex.vue', 'resources/js/Pages/Admin' . $namespace . '/' . $params['Name'] . '/Index.vue', $params);
//        $this->one('Request.php', 'app/Http/Requests' . $namespace . '/' . $params['Name'] . 'Request.php', $params);
//        $this->one('migration', 'database/migrations/' . date('Y_m_d') .'_060000_' . $params['table'] . '.php', $params);

    }


    public function one($src, $dst, $params){

        if (file_exists(base_path($dst)) && !$this->options('force')){
            $this->line($dst . ' already exists');
            return;
        }

        $content = file_get_contents(resource_path('/stubs/' . $src . '.stub'));
        foreach ($params as $name => $value){
            $content = str_replace('{' . $name . '}', $value, $content);
        }

        $dir = pathinfo(base_path($dst), PATHINFO_DIRNAME);
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        file_put_contents(base_path($dst), $content);


    }

}
