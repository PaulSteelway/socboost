<?php

namespace App\Repositories;

use App\Models\BlogEntry;
use App\Repositories\BaseRepository;

/**
 * Class BlogEntryRepository
 * @package App\Repositories
 * @version June 1, 2020, 12:56 pm UTC
 *
 * @method BlogEntry findWithoutFail($id, $columns = ['*'])
 * @method BlogEntry find($id, $columns = ['*'])
 * @method BlogEntry first($columns = ['*'])
*/
class BlogEntryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'blog',
        'publish_after',
        'slug',
        'title',
        'author_name',
        'author_email',
        'author_url',
        'image',
        'content',
        'summary',
        'page_title',
        'description',
        'meta_tags',
        'display_full_content_in_feed'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BlogEntry::class;
    }


    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
}
