<?php  defined("IN_PHPCMS") or exit("No permission resources."); pc_base::load_app_class("admin","admin",0); $this_username = param::get_cookie("admin_username"); $this_userid = param::get_cookie("admin_userid"); $db = pc_base::load_model("waybill_model"); $no = trim($_GET["no"]); $result = array("color"=>"#FF6600","time"=>time()); if($no && !empty($this_username)){ $row = $db->get_one(array("expressno"=>$no,"status"=>7),"expressno"); $result["color"] = "#CAFF70"; if($row){ $data = array(); $data["operatorid"] = SYS_TIME; $data["operatorname"] = $this_username; $data["status"] = 10; $db->update($data,array("expressno"=>$no)); update_all_package_status($no); } } print_r(json_encode($result)); function update_all_package_status($expressno,$send_email_nofity=1){ $cdb = pc_base::load_model("waybill_goods_model"); $pdb = pc_base::load_model("package_model"); $wdb = pc_base::load_model("waybill_model"); $expressno="91002933"; $rs = $wdb->get_one(array("expressno"=>$expressno)); if($rs){ $moduledb = pc_base::load_model("module_model"); $__mdb = pc_base::load_model("member_model"); $userinfo=$__mdb->get_one(array("userid"=>$rs["userid"])); $status_array=array(7=>"已付款",10=>"待发货"); if($send_email_nofity==1){//发邮件 pc_base::load_sys_func("mail"); $member_setting = $moduledb->get_one(array("module"=>"member"), "setting"); $member_setting = string2array($member_setting["setting"]); $url = APP_PATH."index.php?m=package&c=index&a=init&hid=".$rs["storageid"]; $message = $member_setting["expressnoemail"]; $message = str_replace(array("{click}","{url}","{no}","{status}"), array("<a href=\"".$url."\">".L("please_click")."</a>",$url,$expressno,$status_array[$rs["status"]]), $message); sendmail($userinfo["email"], "你的运单号".$expressno.$status_array[$rs["status"]], $message); } } $wdb->update(array("status"=>10),array("waybillid"=>$waybillid));//更新运单已入库状态 //---------------------------------------------------- $historydb = pc_base::load_model("waybill_history_model"); $row = $wdb->get_one(array("expressno"=>$expressno, "returncode"=>$returncode)); $handle=array(); $handle["siteid"] = 1; $handle["username"] = "admin"; $handle["userid"] = "1"; $handle["addtime"] = SYS_TIME; $handle["sysbillid"] = trim($row["sysbillid"]); $handle["waybillid"] = trim($row["waybillid"]); $handle["placeid"] = $row["storagid"]; $handle["placename"] = $row["storagename"]; $handle["status"] = 10; $handle["remark"] = L("waybill_status".$handle["status"]);	 $lid = $historydb->insert($handle); //---------------------------------------------------- }//end update_all_status?>