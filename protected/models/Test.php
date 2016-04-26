<?php

/**
 * Description of Test
 *
 * @author Administrator
 */
class Test extends WebModels{
    public function add_data()
    {
        Yii::import('system.extension.splitword.SplitWord');

        $string = '福建莆田整形莆田医院';
        //echo 'original : '.$string . chr(10);
        $sp = ClassLoad::Only('SplitWord');/* @var $sp SplitWord */
        $sp->SetSource($string);
        $sp->SetResultType(2);
        $sp->StartAnalysis(true);
        $titleindexs = $sp->GetFinallyResultArray(false);  
        //临时存放数组
        $temp = array();
        $transaction = Yii::app()->getDb()->beginTransaction();
        try {
            foreach ($titleindexs as $word => $num){
                $this->insert("search_tag", array(
                    'gid'=>4,
                    'type'=>1,
                    'word_crc32'=>sprintf('%u' , crc32($word)) . chr(10)
                ));            
            }
            $transaction->commit();
            return true;
        } catch (Exception $exc) {
            $transaction->rollback();
            return false;
        }
    }
    /*
     * 执行 关键字 搜索
     * @param   array   $arr    搜索关键字转换的crc32编码
     */
    public function search_word( array $arr)
    {
        $crc32_str = implode(",", $arr);
        $sql = "SELECT gid FROM search_tag WHERE word_crc32 in(4291880431 ,2001119010)";
        $res = $this->queryAll($sql);
        //将二维数组 转换成一维 gid 唯一
        $temp = array();
        if(!empty($res)){
            foreach ($res as $k=>$row) {
                $temp[] = $row['gid'];
            }
            return array_unique($temp);
        }else{
            return false;
        }
    }
}
