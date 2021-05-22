<?php
class Travel
{
    // Enter your code here
    public function list()
    {
        $data = file_get_contents("https://5f27781bf5d27e001612e057.mockapi.io/webprovise/travels");
        return json_decode($data,true);
    }
}
class Company
{
    // Enter your code here
    public function list()
    {
        $data = file_get_contents("https://5f27781bf5d27e001612e057.mockapi.io/webprovise/companies");
        return json_decode($data,true);
    }
}
class TestScript
{
    public function execute()
    {
        $start = microtime(true);
        // Enter your code here
        $travel = new Travel();
        $travelList = $travel->list();

        $company = new Company();
        $companyList = $company->list();

        $new = array();
        $i = 0;
        foreach ($companyList as $t){
            $new[$t['parentId']][$i]['id'] = $t['id'];
            $new[$t['parentId']][$i]['name'] = $t['name'];
            $new[$t['parentId']][$i]['cost'] = 0;
            foreach ($travelList as $c) {
                if( $t['id'] == $c['companyId']){
                    $new[$t['parentId']][$i]['cost'] = $c['price'];
                }
            }
            $i++;
        }
        
        $result = $this->createParentData($new, $new[0]);
        print_r(json_encode($result));die;
    }
    public function createParentData(&$list, $parent){
        $result = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createParentData($list, $list[$l['id']]);
            }
            else{
                $l['children'] = [];
            }
            $result[] = $l;
        } 
        return $result;
    }
}
(new TestScript())->execute();
