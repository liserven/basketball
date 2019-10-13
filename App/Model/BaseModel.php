<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-09-22
 * Time: 17:41
 */

namespace App\Model;


use App\Utility\Pool\MysqlConnection;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Singleton;
use ezswoole\pool\MysqlObject;

abstract class BaseModel
{

    private $db;
    protected $table;

    use Singleton;

    public function __construct(MysqlConnection $mysqlObject)
    {
        $this->db = $mysqlObject;
    }

    public function getCommonWhere( $where = [] )
    {
        if( is_array( $where ) )
        {
            foreach ( $where as $key => $val )
            {
                if( is_array($val ) )
                {
                    $this->db->where($key, $val[1], $val[0]);
                }else{
                    $this->db->where($key , $val , '=');
                }
            }
        }else{
            throw new \Exception('查询条件格式错误');
        }
        return $this;
    }


    protected function getCommonGroupBy( string $groupBy )
    {
        if( !empty($groupBy ) )
        {
            $this->db->groupBy($groupBy);
        }
        return $this;
    }

    protected function getCountTotal( $isTotal = true )
    {
        if( $isTotal === true )
        {
            $this->db->withTotalCount();
        }
        return $this;
    }

    protected function getCommonJoin( array $joins = [] )
    {

        if( !empty($joins ) )
        {
            foreach ($joins as $key => $join )
            {
                if( is_array($join) )
                {
                    $this->db->join($key, $join[0], $join[1]);
                }
            }
        }
        return $this;
    }

    protected function getCommonOrderBy( $order = '' )
    {
        if( !empty($order) )
        {
            $orderArr = explode(' ', $order);
            $this->db->orderBy($orderArr[0], $orderArr[1]);
        }
        return $this;
    }

    public function updated(array $where = [] , array $data )
    {
        if( !empty($where) )
        {
            throw new \Exception('无更新条件');
        }
        $obj = $this->getCommonWhere($where);
        $this->db->update($this->table,  $data);
    }

    public function deleted(array $where = [], $limit = 1 )
    {
        $obj = $this->getCommonWhere($where);
        return $obj->db->delete($this->table, $limit);
    }

    public function add (array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function findOne ( $where = [] , $field = '*' , $join = [] )
    {
        $obj = $this->getCommonWhere($where);
        return $obj->db->getOne($this->table, $field);
    }


    public function findAll(array $where = [], $field = '*', $page = 1 , $limit = 10, array $join = [], $order = [], $group = '', $isTotal = true ) {
        $obj = $this->getCommonWhere($where)->getCommonGroupBy($group)->getCountTotal($isTotal)->getCommonJoin($join)->getCommonOrderBy($order);
        return $obj->db->get($this->table, [$page, $limit], $field);
    }


    protected function returnData ($data)
    {
        if( !empty($data ) )
        {
            return array(

            );
        }
        return [];
    }

    public function getDb()
    {
        if( $this->db instanceof MysqlObject )
        {
            return $this->db;
        }
        $this->db = MysqlPool::defer();
        return $this->db;
    }

}
