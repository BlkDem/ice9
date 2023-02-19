<?php 
require_once "dbc_all.php";
require_once "db.php";

class dbpager extends db
{
    private $_db;
    public $pageno;
    public $pagenext;
    public $pageprev;
    public $no_of_records_per_page;
    public $offset;
    public $total_pages;
    public $page_list;
    public function __construct($_records_per_page, $_dbtable) 
    {
        $this->_db = new db();
        $this->pageno = (isset($_GET['pageno']))?$_GET['pageno']:1;
        $this->no_of_records_per_page = $_records_per_page;
        $this->offset = ($this->pageno-1) * $this->no_of_records_per_page;
        $pages_result = mysqli_query($this->_db->dblink, "SELECT COUNT(*) FROM " . $_dbtable);
        $total_rows = mysqli_fetch_array($pages_result)[0];
        $this->total_pages = floor($total_rows / $this->no_of_records_per_page);
        if (($total_rows % $this->no_of_records_per_page) > 0) {$this->total_pages++;};
        
        $this->pageprev = (($this->pageno)>1)?$this->pageno-1:1;
        $this->pagenext = (($this->pageno)>=($this->total_pages))?$this->total_pages:$this->pageno+1;
        
        $this->page_list = "<button class=\"btn btn-outline-primary dropdown-toggle\" id=\"dropdownMenuButton\" 
                      type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Страница $this->pageno</button>
                      <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">";
        for ($i = 1; $i <= $this->total_pages; $i++) 
        {
           $this->page_list .= "<a class='dropdown-item btn-outline-primary' href=\"?pageno=$i\">Страница $i</a>";
        }
        $this->page_list .= "</div>";
    }
    function __destruct() {
       $this->_db->dblink->close();
    }
}


?>