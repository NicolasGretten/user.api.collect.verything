<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PaginationTrait
{
    /**
     * @var mixed $pagination
     *
     * @return $this
     */
    public $pagination = false;

    public function paginate(Builder $builder)
    {
        $limit = request()->get('limit');

        if(!empty($limit) and (int) $limit > 0) {
            $pagination = $builder->paginate($limit);

            $pagination->setPath($this->makePath());
            $this->pagination = json_encode([
                'current_page'  => (int) $pagination->currentPage(),
                'last_page'     => (int) $pagination->lastPage(),
                'per_page'      => (int) $pagination->perPage(),
                'total'         => (int) $pagination->total(),
                'next_page_url' => $pagination->nextPageUrl(),
                'prev_page_url' => $pagination->previousPageUrl()
            ]);
        }

        return $this;
    }

    private function makePath(): string
    {
        $parameters = [];
        $explodedQueryString = explode('&', Request()->getQueryString());
        foreach ($explodedQueryString as $string) {
            $values = explode('=', $string);
            $key = $values[0];
            $val = $values[1];

            if($key == '_url' or $key == 'page') {
                continue;
            }

            $parameters[$key] = $val;
        }

        return env('APP_URL') . Request()->getPathInfo() . '?' . http_build_query($parameters);
    }
}
