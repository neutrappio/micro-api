<?php

namespace Mapi\Traits;

use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Paginator\RepositoryInterface;
use Phalcon\Mvc\Model\Query\BuilderInterface;
use Phalcon\Paginator\Adapter\AdapterInterface;

trait TPagination
{
    /**
     * Get Limit
     *
     * @return integer
     */
    public function getLimit() : int
    {
        $limit = $this->request->getQuery('limit', 'int', 20);

        if ($limit < 0) {
            $limit = 1;
        }

        return $limit;
    }

    /**
     * Get current page
     *
     * @return integer
     */
    public function getPage() : int
    {
        $page = $this->request->getQuery('page', 'int', 0);

        if ($page < 0) {
            $page = 0;
        }

        return $page;
    }

    /**
     * Generate paginator
     *
     * @param BuilderInterface $builder
     * @return QueryBuilder
     */
    public function generatePaginator(BuilderInterface $builder) : QueryBuilder
    {
        $paginator = new QueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $this->getLimit(),
                "page"    => $this->getPage(),
            ]
        );

        return $paginator;
    }

    /**
     * Get Paginate
     *
     * @param AdapterInterface $paginator
     * @return RepositoryInterface
     */
    public function getPaginate(AdapterInterface $paginator) : RepositoryInterface
    {
        return $paginator->paginate();
    }

    /**
     * Get Paginate
     *
     * @param RepositoryInterface $paginate
     * @return array
     */
    public function getPagination(RepositoryInterface $paginate) : array
    {
        return [
            'previous'=> $paginate->getPrevious(),
            'current'=> $paginate->getCurrent(),
            'next'=> $paginate->getNext(),
            'last'=> $paginate->getLast(),
            'limit'=> $paginate->getLimit(),
            'total'=> $paginate->getTotalItems(),
        ];
    }


    /**
     * Get Full Pagination
     *
     * @param BuilderInterface $builder
     * @return array
     */
    public function getFullPagination(BuilderInterface $builder) : array
    {
        $paginate = $this->getPaginate($this->generatePaginator($builder));

        return [
            'items' => $paginate->getItems(),
            'pagination'=> $this->getPagination($paginate)
        ];
    }
}
