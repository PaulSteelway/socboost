<?php

namespace App\Models;

use App\Models\UserTasks\Tasks;
use App\Models\UserTasks\UserTasks as TaskUser;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Category
 * @package App\Models
 * @version November 26, 2019, 9:07 pm UTC
 *
 * @property Category parent
 * @property \Illuminate\Database\Eloquent\Collection packets
 * @property integer id
 * @property integer parent_id
 * @property string name_ru
 * @property string name_en
 * @property integer status
 * @property integer order
 * @property string url
 * @property string type
 * @property string details_ru
 * @property string details_en
 */
class Category extends Model
{

  public $table = 'categories';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'parent_id',
    'name_ru',
    'name_en',
    'icon_img',
    'icon_img_active',
    'icon_class',
    'status',
    'order',
    'url',
    'type',
    'details_ru',
    'details_en',
    'details_title_ru',
    'details_title_en',
    'icon_title1',
    'icon_title2',
    'icon_title3',
    'icon_title4',
    'icon_subtitle1',
    'icon_subtitle2',
    'icon_subtitle3',
    'icon_subtitle4',
    'icon_title1_ru',
    'icon_title2_ru',
    'icon_title3_ru',
    'icon_title4_ru',
    'icon_subtitle1_ru',
    'icon_subtitle2_ru',
    'icon_subtitle3_ru',
    'icon_subtitle4_ru',
    'info',
    'info_rus',
    'meta_title',
    'meta_title_ru',
    'meta_description',
    'meta_description_ru',
    'title',
    'title_ru',
    'meta_keywords',
    'meta_keywords_ru',
    'free_promotion',
  ];

  /**
  * The attributes that should be casted to native types.
  *
  * @var array
  */
  protected $casts = [
    'id' => 'integer',
    'parent_id' => 'integer',
    'name_ru' => 'string',
    'name_en' => 'string',
    'icon_class' => 'string',
    'icon_img' => 'string',
    'icon_img_active' => 'string',
    'status' => 'integer',
    'order' => 'integer',
    'url' => 'string',
    'type' => 'string',
    'details_title_en' => 'string',
    'details_title_ru' => 'string',
    'details_en' => 'string',
    'details_ru' => 'string',
    'icon_title1' => 'string',
    'icon_title2' => 'string',
    'icon_title3' => 'string',
    'icon_title4' => 'string',
    'icon_subtitle1' => 'string',
    'icon_subtitle2' => 'string',
    'icon_subtitle3' => 'string',
    'icon_subtitle4' => 'string',
    'icon_title1_ru' => 'string',
    'icon_title2_ru' => 'string',
    'icon_title3_ru' => 'string',
    'icon_title4_ru' => 'string',
    'icon_subtitle1_ru' => 'string',
    'icon_subtitle2_ru' => 'string',
    'icon_subtitle3_ru' => 'string',
    'icon_subtitle4_ru' => 'string',
    'info' => 'string',
    'info_rus' => 'string',
    'meta_keywords' => 'string',
    'meta_keywords_ru' => 'string',
  ];

  /**
  * Validation rules
  *
  * @var array
  */
  public static $rules = [
    'parent_id' => 'exists:categories,id|nullable',
    'icon_class' => 'required',
    'name_ru' => 'required|string|max:191',
    'name_en' => 'required|string|max:191',
    'order' => 'required|integer',
    'url' => 'string|max:191|unique:categories,url|nullable',
    'type' => 'nullable|string',
    'details_title_ru' => 'nullable|string',
    'details_title_en' => 'nullable|string',
    'details_ru' => 'nullable|string',
    'details_en' => 'nullable|string',
    'icon_title1' => 'nullable|string',
    'icon_title2' => 'nullable|string',
    'icon_title3' => 'nullable|string',
    'icon_title4' => 'nullable|string',
    'icon_subtitle1' => 'nullable|string',
    'icon_subtitle2' => 'nullable|string',
    'icon_subtitle3' => 'nullable|string',
    'icon_subtitle4' => 'nullable|string',
    'icon_title1_ru' => 'nullable|string',
    'icon_title2_ru' => 'nullable|string',
    'icon_title3_ru' => 'nullable|string',
    'icon_title4_ru' => 'nullable|string',
    'icon_subtitle1_ru' => 'nullable|string',
    'icon_subtitle2_ru' => 'nullable|string',
    'icon_subtitle3_ru' => 'nullable|string',
    'icon_subtitle4_ru' => 'nullable|string',
    'info' => 'nullable|string',
    'info_rus' => 'nullable|string',
  ];


  //scopes
  public function scopeHeader($query) {
    return $query->whereNull('parent_id');
  }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function packets()
    {
        return $this->hasMany(Packet::class);
    }

    /**
     **/
    public function tasks()
    {
        return $this->hasMany(UserTasks\Tasks::class);
    }


    /**
     **/
    public function tasksCount($userTasks = false)
    {
        /*Task completed, hidden or blacklisted*/
        $excluded_tasks = TaskUser::where('user_id', \Auth::id())->whereIn('status', [2, 3])->pluck('task_id', 'id');
        if (!$this->parent_id) {
            if ($userTasks) {
                $tasks = $this->allChildCategoriesTasks()->where('user_id', \Auth::id())
                    ->whereNotIn('id', $excluded_tasks)
                    ->whereRaw('tasks.execution_qty > tasks.executed_qty')
                    ->whereRaw('tasks.execution_qty > (SELECT COUNT(ut.id) FROM user_tasks ut WHERE ut.task_id = tasks.id)')
                    ->count();
            } else {
                $tasks = $this->allChildCategoriesTasks()->where('user_id', '!=', \Auth::id())
                    ->whereRaw('tasks.execution_qty > tasks.executed_qty')
                    ->whereRaw('tasks.execution_qty > (SELECT COUNT(ut.id) FROM user_tasks ut WHERE ut.task_id = tasks.id)')
                    ->whereNotIn('id', $excluded_tasks)
                    ->count();
            }
        } else {
            if ($userTasks) {
                $tasks = $this->hasMany(UserTasks\Tasks::class)->where('user_id', \Auth::id())
                    ->whereNotIn('id', $excluded_tasks)
                    ->whereRaw('tasks.execution_qty > tasks.executed_qty')
                    ->whereRaw('tasks.execution_qty > (SELECT COUNT(ut.id) FROM user_tasks ut WHERE ut.task_id = tasks.id)')
                    ->count();
            } else {
                $tasks = $this->hasMany(UserTasks\Tasks::class)->where('user_id', '!=', \Auth::id())
                    ->whereNotIn('id', $excluded_tasks)
                    ->whereRaw('tasks.execution_qty > tasks.executed_qty')
                    ->whereRaw('tasks.execution_qty > (SELECT COUNT(ut.id) FROM user_tasks ut WHERE ut.task_id = tasks.id)')
                    ->count();
            }
        }
        return $tasks;
    }

    /**
     **/
    public function allChildCategoriesTasks()
    {
        return UserTasks\Tasks::whereIn('category_id', $this->child_categories()->pluck('id'));
    }


    public function subcategories() {
      return $this->hasMany(Category::class, 'parent_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * deprecated
     **/
    public function child_categories() {
      return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function category_add_pages()
    {
        return $this->hasMany(CategoryAddPage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function child_free_prom_categories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->where('free_promotion', 1);
    }
}
