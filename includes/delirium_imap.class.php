<?php
class Delirium_Mail{
	var $mbox;
	var $num_messages;
	var $list;
	var $arr_images;
	
	function __construct($user,$pass,$server,$port="110",$type="pop3",$box="INBOX"){
		$ServerName="{{$server}/$type:$port}$box";
		$this->mbox = imap_open($ServerName, $user,$pass) or die("No se pudo abrir la cuenta!"); 
		
		if ($oImap = imap_check($this->mbox)) {
	   		$this->num_messages=$oImap->Nmsgs;
	   	}else{
	   		$this->num_messages=0;
   		}
		
	}
	
	function __destruct(){
		  imap_close($this->mbox);
	}
	
	function fetch_list($sequence="1"){
		$this->list=imap_fetch_overview($this->mbox,$sequence,0);	
		
		return $this->list;
	}
	
	function get_mime_type(&$structure) {
		$primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
		
		if($structure->subtype) {
			return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
		}
		
		return "TEXT/PLAIN";
	}
	
	function get_part($stream, $msg_number, $mime_type, $structure = false,$part_number    = false) {
	
		if(!$structure) {
			$structure = imap_fetchstructure($stream, $msg_number);
		}
		if($structure) {
			if($mime_type == get_mime_type($structure)) {
				if(!$part_number) {
					$part_number = "1";
				}
				$text = imap_fetchbody($stream, $msg_number, $part_number);
				if($structure->encoding == 3) {
					return imap_base64($text);
				}else if($structure->encoding == 4) {
					return imap_qprint($text);
				}else{
					return $text;
				}
			}
		
			if($structure->type == 1) /* multipart */ {
				while(list($index, $sub_structure) = each($structure->parts)) {
					if($part_number) {
						$prefix = $part_number . '.';
					}
					$data = get_part($stream, $msg_number, $mime_type, $sub_structure,$prefix .    ($index + 1));
					if($data) {
						return $data;
					}
				}
			}
		}
		return false;
	}
	
	function get_attachments($id_msg){
		$struct = imap_fetchstructure($this->mbox,$id_msg);
		$contentParts = count($struct->parts);
		
		if ($contentParts >= 2) {
			for ($i=2;$i<=$contentParts;$i++) {
				$att[$i-2] = imap_bodystruct($this->mbox,$id_msg,$i);
			}
			for ($k=0;$k<sizeof($att);$k++) {
				if ($att[$k]->parameters[0]->value == "us-ascii" || $att[$k]->parameters[0]->value    == "US-ASCII") {
					if ($att[$k]->parameters[1]->value != "") {
						$selectBoxDisplay[$k] = $att[$k]->parameters[1]->value;
					}
				}elseif($att[$k]->parameters[0]->value != "iso-8859-1" &&    $att[$k]->parameters[0]->value != "ISO-8859-1") {
					$selectBoxDisplay[$k] = $att[$k]->parameters[0]->value;
				}
			}
		}
		
		if (sizeof($selectBoxDisplay) > 0) {
			echo "<select name=\"attachments\" size=\"3\" class=\"tblContent\"    onChange=\"handleFile(this.value)\" style=\"width:170;\">";
			for ($j=0;$j<sizeof($selectBoxDisplay);$j++) {
				echo "\n<option value=\"$j\">". $selectBoxDisplay[$j]    ."</option>";
			}
			echo "</select>";
		}
	}
	
	function get_images($id_msg){
		
		$struct = imap_fetchstructure($this->mbox,$id_msg);
		$contentParts = count($struct->parts);
		
		if ($contentParts >= 2) {
			for ($i=2;$i<=$contentParts;$i++) {
				$att[$i-2] = imap_bodystruct($this->mbox,$id_msg,$i);
			}
			for ($k=0;$k<sizeof($att);$k++) {
				if ($att[$k]->parameters[0]->value == "us-ascii" || $att[$k]->parameters[0]->value    == "US-ASCII") {
					if ($att[$k]->parameters[1]->value != "") {
						$selectBoxDisplay[$k] = $att[$k]->parameters[1]->value;
					}
				}elseif($att[$k]->parameters[0]->value != "iso-8859-1" &&    $att[$k]->parameters[0]->value != "ISO-8859-1") {
					$selectBoxDisplay[$k] = $att[$k]->parameters[0]->value;
				}
			}
		}
		
		if (sizeof($selectBoxDisplay) > 0) {			
			for ($j=0;$j<sizeof($selectBoxDisplay);$j++) {
				echo "<img src=\"root.php?do=mail::get_image&id_file=$j&id_msg=$id_msg\" />";
			}			
		}	

		$this->arr_images=$selectBoxDisplay;
	}
	
	function get_image($id_msg,$id_file){
		$strFileName = $selectBoxDisplay[$id_file];
	   	$strFileType = strrev(substr(strrev($strFileName),0,4));
	   	$fileContent = imap_fetchbody($this->mbox,$id_msg,$file+2);
		
		
		$ContentType = "application/octet-stream";
   
	   	if ($strFileType == ".asf") 
	   		$ContentType = "video/x-ms-asf";
	   	if ($strFileType == ".avi")
	   		$ContentType = "video/avi";
	   	if ($strFileType == ".doc")
	   		$ContentType = "application/msword";
	   	if ($strFileType == ".zip")
	   		$ContentType = "application/zip";
	   	if ($strFileType == ".xls")
	   		$ContentType = "application/vnd.ms-excel";
	   	if ($strFileType == ".png")
	   		$ContentType = "image/png";
	   	if ($strFileType == ".gif")
	   		$ContentType = "image/gif";
	   	if ($strFileType == ".jpg" || $strFileType == "jpeg")
	   		$ContentType = "image/jpeg";
	   	if ($strFileType == ".wav")
	   		$ContentType = "audio/wav";
	   	if ($strFileType == ".mp3")
	   		$ContentType = "audio/mpeg3";
	   	if ($strFileType == ".mpg" || $strFileType == "mpeg")
	   		$ContentType = "video/mpeg";
	   	if ($strFileType == ".rtf")
	   		$ContentType = "application/rtf";
	   	if ($strFileType == ".htm" || $strFileType == "html")
	   		$ContentType = "text/html";
	   	if ($strFileType == ".xml") 
	   		$ContentType = "text/xml";
	   	if ($strFileType == ".xsl") 
	   		$ContentType = "text/xsl";
	   	if ($strFileType == ".css") 
	   		$ContentType = "text/css";
	   	if ($strFileType == ".php") 
	   		$ContentType = "text/php";
	   	if ($strFileType == ".asp") 
	   		$ContentType = "text/asp";
	   	if ($strFileType == ".pdf")
	   		$ContentType = "application/pdf";
	   
		header ("Content-Type: $ContentType"); 
		//header ("Content-Disposition: attachment; filename=$strFileName; size=$fileSize;"); 
		
		
		if (substr($ContentType,0,4) == "text") {
		echo imap_qprint($fileContent);
		} else {
		echo imap_base64($fileContent);
		}
	}
	
}
?>