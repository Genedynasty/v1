<?php
class ReferenceManager {
    const TYPE_ANY          =       0;
    const TYPE_ARTICLE      =       1;
    const TYPE_VIDEO        =       2;
    const TYPE_IMAGEGALLERY =       3;
    const TYPE_VOTE         =       4;

    const QUERYTYPE_AND = 0;
    const QUERYTYPE_OR = 1;

    public static $REFID_KEYS = array(
        //self::TYPE_BLOG => array("blogid","postid") <--- példa
    );

    
    public static function getIdsByObjs($objs) {
        if (!is_array($objs)) return array(0=>$objs->getID());
        $ids=array();
        foreach ($objs as $obj) {
            $id=$obj->getID();
            $ids[$id]=$id;
        }
        return $ids;
    }

    public static function getTypeSQL($type) {
        if ($type==self::TYPE_ANY) return "1=1";
        if (is_array($type)) return "type IN (".implode(",",$type).")";
        return "type=$type";
    }

    public static function getRefIds($type, $refid) {
        $refid_1=0;
        $refid_2=0;
        if (isset(self::$REFID_KEYS[$type])) {
            
            if (isset($refid[self::$REFID_KEYS[$type][0]]))
                $refid_1=(int)$refid[self::$REFID_KEYS[$type][0]];
            elseif (isset($refid[0])) $refid_1=(int)$refid[0];
            else $refid_1=(int)$refid;

            
            if (isset(self::$REFID_KEYS[$type][1])) {
                if (isset($refid[self::$REFID_KEYS[$type][1]]))
                    $refid_2=(int)$refid[self::$REFID_KEYS[$type][1]];
                elseif (isset($refid[1])) $refid_2=(int)$refid[1];

                if ($refid_2==0) throw new Exception("Hibás refid specifikáció2!");
            }
        } else $refid_1=(int)$refid;

        if ($refid_1==0) throw new Exception("Hibás refid specifikáció3!");

        return array($refid_1, $refid_2);
    }

    public static function getRefIdSQL($type, $refid) {
        $refs=self::getRefIds($type, $refid);
        $ret="refid_1=$refs[0] AND refid_2=$refs[1]";
        return $ret;
    }
}