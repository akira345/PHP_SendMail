<?php
//�᡼�륯�饹
//��ǽ����ñ�ʥƥ�ץ졼���ɤ߹��ߡ��᡼������
//���¡�ź�ե᡼����Բ�
	mb_internal_encoding("EUC-JP") ;
class sendmail{
	private $template;	//�ƥ�ץ졼�ȥե����롣���줬�ִ������
	private $lang;		//ʸ��������
	private $to;		//To
	private $from;		//From
	private $title;		//�����ȥ�
	private $contents;	//�ִ���������
	
	//���󥹥ȥ饯��
	//�������ƥ�ץ졼�ȥե����롢ʸ��������
	function sendmail($tmp_path,$lang="ja"){
		$this->lang = $lang;
		$this->template = file_get_contents($tmp_path);
		//ʸ���������Ѵ�
		$this->template = $this->conv($this->template);
	}
	
	//�ᥢ�������������å�
	//http://fdays.blogspot.com/2007/10/rfc-2822-j0hn-d0e-10-pregmatch-9.html����Ҽ�
	private function chk_email($value){
		return preg_match('/^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i', $value);
	}
	//ʸ���������Ѵ�
	private function conv($in_data){
		return mb_convert_encoding($in_data,"EUC-JP", "EUC-JP,UTF-8,SJIS,JIS,ASCII");//auto����ʸ���������뤿��
	}

	//�ץ�ѥƥ�����
	//To�����
	function set_to($to){
		If ($this->chk_email($to)){
			$this->to = $to;
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//From�����
	function set_from($from){
		If ($this->chk_email($from)){
			$this->from = $from;
			return TRUE;
		}else{
		echo $this->chk_email($from);
			return FALSE;
		}
	}
	//Title�����
	function set_title($title){
		If ($title){
			$this->title = $title;
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	protected function replace_options($matches) {
		//�����ˤ�preg_replace_callback���ޥå�����ʸ���󤬰����Ϥ����
		//$this->contents����˥ޥå�����ʸ����Υ�����¸�ߤ�����硢
		//�ޥå������������б������������Ȥ��֤���
		//�ޥå����ʤ���Х֥�󥯤��֤�
		print_r( $matches);
	  	if (array_key_exists($matches[1], $this->contents)) {
	      return $this->contents[$matches[1]];
	    } else {
	      return "";
	    }
	  }

	//�᡼������
	function send($in_data = array()){
		$this->contents = $in_data;
		//�ƥ�ץ졼�Ȥ�������ͤ򥻥å�
		//http://d.hatena.ne.jp/tilfin/20080714/1216108930/�򻲹ͤˤ���
		//�ִ��о�ʸ�����{$XXX}�ǡ�XXX�βս��Ⱦ�ѱѿ�a-z,0-9�ޤ�
		$content = preg_replace_callback('/\{\$([a-z0-9]+)\}/',
                 array($this, "replace_options"), $this->template);
		
		//���å�
		$this->title = str_replace(array("\r\n","\n","\r"), '', $this->title);//�����ȥ�ϲ��Ԥ�����Ȥ����ʤ�
		$this->title = trim($this->title);//ǰ�Τ���
		$this->from = "From:" . $this->from;//from
		//��ʸ�β��ԥ����ɤ�LF�Τ�
		$content = str_replace(array("\r\n","\n","\r"), "\n", $content);
		//��Ԥϣ���ʸ���ޤ�
		$content = wordwrap($content, 70);
		
		//ʸ���������Ѵ�
		$this->to = $this->conv($this->to);
		$this->title = $this->conv($this->title);
		$content = $this->conv($content);
		$this->from = $this->conv($this->from);
		
		//����
		mb_language($this->lang);
		return mb_send_mail($this->to,$this->title,$content,$this->from);
	
	}
}

?>
