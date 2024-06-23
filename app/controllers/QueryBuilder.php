<?php
namespace App;
use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder {

    private $pdo;
    private $queryFactory;
    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo =$pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getAllUsers()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('users_data');

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }

    public function getUserDataById($user_id)
    {

        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('users_data')
            ->where('user_id = :user_id')
            ->bindValue('user_id', $user_id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

    public function insertUserInfo($table, $data)
    {

        $insert = $this->queryFactory->newInsert();

        $insert
            ->into($table)                   // INTO this table
            ->cols($data);

        // prepare the statement
        $sth = $this->pdo->prepare($insert->getStatement());
        // execute with bound values
        $sth->execute($insert->getBindValues());

    }


    public function editUserInfo($table, $user_id, $data)
    {

        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('user_id = :user_id') // AND WHERE these conditions
            ->bindValue('user_id', $user_id);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());

    }


    public function deleteUserInfo($table, $user_id)
    {



        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                   // FROM this table
            ->where('user_id = :user_id')
            ->bindValue('user_id', $user_id);

        // prepare the statement
        $sth = $this->pdo->prepare($delete->getStatement());

         // execute with bound values
        $sth->execute($delete->getBindValues());

    }

    public function deleteUser($table, $id)
    {



        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                   // FROM this table
            ->where('id = :id')
            ->bindValue('id', $id);

        // prepare the statement
        $sth = $this->pdo->prepare($delete->getStatement());

        // execute with bound values
        $sth->execute($delete->getBindValues());

    }

}