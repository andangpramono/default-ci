<?php
 
 
class Object_model extends CI_Model {
 
    var $table = 'sma_brands';

    public function __construct()
    {
        parent::__construct();
    }

    ///// select /////

    public function all($order='id', $type='asc', $limit=0, $offset=0){
        $this->db->select('*')
            ->from($this->table)
            ->order_by($order, $type);
        
        if($limit != 0){
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    public function first($order='id', $type='asc'){
        $this->db->select('*')
            ->from($this->table)
            ->order_by($order, $type);
        
        return $this->db->get()->row();
    }

    public function findAll($coloumn, $search, $limit=0, $offset=0){
        $this->db->select('*')
            ->from($this->table)
            ->where($coloumn, $search);

        if($limit != 0){
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function findFirst($coloumn, $search){
        $this->db->select('*')
            ->from($this->table)
            ->where($coloumn, $search);
        
        return $this->db->get()->row();
    }

    public function findWhereRawAll($raw, $limit=0, $offset=0){
        $this->db->select('*')
            ->from($this->table)
            ->where($raw);

        if($limit != 0){
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();

    }

    public function findWhereRawFirst($raw){
        $this->db->select('*')
            ->from($this->table)
            ->where($raw);

        return $this->db->get()->row();

    }

    ///// insert //////

    ///// update /////

    public function update($data, $coloumn, $search){
        $this->db->update($this->table, $data, array($coloumn => $search));
    }

    public function updateWhere($data, $where){
        $this->db->update($this->table, $data, $where);
    }

    ///// delete /////

    ///// custom /////
 
    ///// custom datatables server side //////

    private function _get_datatables_query(){
        $_POST=$this->input->post();
        $this->db->from($this->table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables(){
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
}