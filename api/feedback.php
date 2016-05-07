<?php
defined('IN_PHPCMS') or exit('No permission resources.');


$content_db = pc_base::load_model('content_model');
			$content_db->set_model(3);


			$typename = isset($_POST['typename']) ? $_POST['typename'] : exit;

			$ComplaintName = isset($_POST['ComplaintName']) ? $_POST['ComplaintName'] : '';
			$Sex = isset($_POST['Sex']) ? $_POST['Sex'] : '';
			$ContactFirst = isset($_POST['ContactFirst']) ? $_POST['ContactFirst'] : '';


			$qstype = isset($_POST[info]['title']) ? trim($_POST[info]['title']) : '';
			$qsremark = isset($_POST[info]['content']) ? trim($_POST[info]['content']) : '';

				
			$qstype = $ComplaintName.$Sex.' '.$ContactFirst.' '.$typename.' '.$qstype; 

			if($qstype && $qsremark){
				$datas =array();
				$datas['catid']=1;
				$datas['status']=99;
				
				
				$datas['title']=$qstype;
				$datas['description']=$qsremark;
				$datas['content']=$qsremark;
				
				if($content_db->add_content($datas)){
					showmessage('您已成功提问!', HTTP_REFERER);
				}
			}


?>