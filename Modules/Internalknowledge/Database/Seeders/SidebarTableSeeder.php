<?php

namespace Modules\Internalknowledge\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sidebar;

class SidebarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $check = Sidebar::where('title', __('Internal Knowledge'))->where('parent_id', 0)->exists();
        if (!$check) {
            $main = Sidebar::where('title', __('Internal knowledge'))->where('parent_id', 0)->where('type', 'company')->first();
            if ($main == null) {
                $main = Sidebar::create([
                    'title' => 'Internal knowledge',
                    'icon' => 'ti ti-book',
                    'parent_id' => 0,
                    'sort_order' => 444,
                    'route' => null,
                    'permissions' => 'internalknowledge manage',
                    'module' => 'Internalknowledge',
                    'type' => 'company',

                ]);
            }
            $book = Sidebar::where('title', __('Book'))->where('parent_id', $main->id)->where('type', 'company')->first();
            if ($book == null) {
                Sidebar::create([
                    'title' => 'Book',
                    'icon' => '',
                    'parent_id' => $main->id,
                    'sort_order' => 10,
                    'route' => 'book.index',
                    'permissions' => 'book manage',
                    'module' => 'Internalknowledge',
                    'type' => 'company',

                ]);
            }
            $article = Sidebar::where('title', __('Article'))->where('parent_id', $main->id)->where('type', 'company')->first();
            if ($article == null) {
                Sidebar::create([
                    'title' => 'Article',
                    'icon' => '',
                    'parent_id' => $main->id,
                    'sort_order' => 15,
                    'route' => 'article.index',
                    'permissions' => 'article manage',
                    'module' => 'Internalknowledge',
                    'type' => 'company',

                ]);
            }
            $my_article = Sidebar::where('title', __('My Article'))->where('parent_id', $main->id)->where('type', 'company')->first();
            if ($my_article == null) {
                Sidebar::create([
                    'title' => 'My Article',
                    'icon' => '',
                    'parent_id' => $main->id,
                    'sort_order' => 20,
                    'route' => 'myarticle.index',
                    'permissions' => 'my article manage',
                    'module' => 'Internalknowledge',
                    'type' => 'company',
                ]);
            }
        }
    }
}