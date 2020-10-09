<?php
namespace App\Controllers;


use Core\DB\QueryBuilder;


class DbTest
{
    public function test()
    {
        var_dump(__METHOD__);


        $id = 12;
        $data = [
            'id' => $id,
            'username' => "username-{$id}",
            'email' => "username-{$id}@mail.com",
        ];

        $q = new QueryBuilder();

        $q->table('users u')
            ->fields('u.*')
            ->fields('a.id AS account_id')
            ->fields('a.name AS account_name')
            ->fields('COUNT(a.id) AS account_count')
            ->join('inner', 'accounts a', 'a.user_id = u.id');

        $q->groupBegin();
        $q->where('u.id', '>', 22);
        $q->where('u.rate', '>', 22.34);
        $q->orWhere('u.status', '=', 1);
        $q->orLike('u.name', 'jora soskin');
        $q->like('u.name', 'jora soskin');
        $q->orNotLike('u.name', 'jora soskin', 'right');
        $q->notLike('u.name', 'jora soskin', 'left');
        $q->groupEnd();

        $q->groupBegin();
        $q->orWhereIn('u.id', [1,2,3]);
        $q->where('a.status', 'not', null);
        $q->groupBegin();
        $q->whereNotIn('u.id', [1,2,3]);
        $q->orWhereNotIn('u.id', [1,2,3]);
        $q->orWhereNotIn('u.status', ['good','bad','decent']);
        $q->groupEnd();
        $q->groupEnd();

        $q->groupBy('u.email')->orderBy('u.id ASC, a.name ASC');
        $q->limit(10)->offset(120);
        $str = $q->get();
        
        $q->insert('users', $data);
        $q->update('users', $data);

        $str = $q->getQuery();
        var_dump($q, $str);
    }
}

