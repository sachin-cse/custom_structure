<?php
class Pager extends REST
{
	/***********************************************************************************
	* int findStart (int limit)
	* Returns the start offset based on $this->_request['page'] and $limit
	***********************************************************************************/
	function findStart($limit, $page) {
		if ((!isset($page)) || ($page == "1")) {
			$start = 0;
			//$this->_request['page'] = 1;
		} else
			$start = ($page-1) * $limit;

		return $start;
	}
	/***********************************************************************************
	* int findPages (int count, int limit)
	* Returns the number of pages needed based on a count and a limit
	***********************************************************************************/
	function findPages($count, $limit) {
		$pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
		return $pages;
	}
	/***********************************************************************************
	* string pageList (int curpage, int pages)
	* Returns a list of pages in the format of "´ < [pages] > ª"
	***********************************************************************************/
    function pageList($curpage, $RequestURI, $pages) {
        $eruArr         = explode('?', $RequestURI);
        $REDIRECT_URL   = str_replace(DOMAIN.'/', '', $eruArr[0]);
        
        $extrQryStr     = '';
        
        if($eruArr[1]){
            $extrQryStrArr = explode('&', $eruArr[1]);
            
            $paramArr = array();
            foreach($extrQryStrArr as $param){
                $pv = explode('=', $param);
                
                if($pv[0] != 'page'){
                    $paramArr[] = $param;
                }
            }
            
            if(sizeof($paramArr)>0)
                $extrQryStr = implode('&', $paramArr);
        }
        
		if (($curpage-1) > 0) {
			if($curpage==2)
				$page_list = ($extrQryStr)? '<a href="'.DOMAIN.'/'.$REDIRECT_URL.'?'.$extrQryStr.'" title="Prev" class="previous_pagi">Prev</a>':'<a href="'.DOMAIN.'/'.$REDIRECT_URL.'" title="Prev" class="previous_pagi">Prev</a>';
			else
				$page_list = ($extrQryStr)? '<a href="'.DOMAIN.'/'.$REDIRECT_URL.'?'.$extrQryStr.'&page='.($curpage-1).'" title="Prev" class="previous_pagi">Prev</a>':'<a href="'.DOMAIN.'/'.$REDIRECT_URL.'?page='.($curpage-1).'" title="Prev" class="previous_pagi">Prev</a>';
		}
		if ($curpage==1)
			$page_list .= '<span title="Prev" class="previous_pagi">Prev</span>';

		$page_list  .= '<ul>';
		/* Print the numeric page list; make the current page unlinked and bold */
		$setlr   = 3;
		$st_code = '';
		if (($start = $curpage - $setlr) <= 1) {
			$start = 1; $st_code = '';
		} else {
			$st_code = '';
		}

		if (($end   = $curpage + $setlr) >= $pages) $end = $pages;
		  $page_list .= $st_code;
        
		for ($i = $start; $i <= $end; $i++) {
			if ($i == $curpage)
				$page_list .= '<li><span>'.$i.'</span></li>';
			else {
				if($i==1)
					$page_list .= ($extrQryStr)? '<li><a href="'.DOMAIN.'/'.$REDIRECT_URL.'?'.$extrQryStr.'" title="Page '.$i.'">'.$i.'</a></li>':'<li><a href="'.DOMAIN.'/'.$REDIRECT_URL.'" title="Page '.$i.'">'.$i.'</a></li>';
				else
					$page_list .= ($extrQryStr)? '<li><a href="'.DOMAIN.'/'.$REDIRECT_URL.'?'.$extrQryStr.'&page='.$i.'" title="Page '.$i.'">'.$i.'</a></li>':'<li><a href="'.DOMAIN.'/'.$REDIRECT_URL.'?page='.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
			}
		}
		if ($curpage + $setlr < $pages)
			$page_list .= '';
     	/* Print the Next and Last page links if necessary */
		$page_list .= '</ul>';
		if (($curpage+1) <= $pages)
			$page_list .= ($extrQryStr)? '<a href="'.DOMAIN.'/'.$REDIRECT_URL.'?'.$extrQryStr.'&page='.($curpage+1).'" title="Next" class="next_pagi">Next</a>':'<a href="'.DOMAIN.'/'.$REDIRECT_URL.'?page='.($curpage+1).'" title="Next" class="next_pagi">Next</a>';
		
		if ($curpage== $pages)
			$page_list .= '<span title="Next" class="next_pagi">Next</span>';

		return $page_list;
    }
	/***********************************************************************************
	* string nextPrev (int curpage, int pages)
	* Returns "Previous | Next" string for individual pagination (it's a word!)
	***********************************************************************************/
	function nextPrev($curpage, $pages, $pageType, $id) {
		$next_prev  = "";
		if (($curpage-1) <= 0)
			$next_prev .= "Prev";
		else
			$next_prev .= "<a class=\"previous_pagi\" href=\"".$_SERVER['PHP_SELF']."?pageType=".$pageType."&id=".$id."&page=".($curpage-1)."\">Prev</a>";
		$next_prev .= " | ";
		if (($curpage+1) > $pages)
			$next_prev .= "Next";
		else
			$next_prev .= "<a class=\"next_pagi\" href=\"".$_SERVER['PHP_SELF']."?pageType=".$pageType."&id=".$id."&page=".($curpage+1)."\">Next</a>";
		return $next_prev;
	}
}
?>