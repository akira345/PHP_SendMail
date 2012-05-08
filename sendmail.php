<?php
//メールクラス
//機能：簡単なテンプレート読み込み、メール送信
//制限：添付メールは不可
	mb_internal_encoding("EUC-JP") ;
class sendmail{
	private $template;	//テンプレートファイル。これが置換される
	private $lang;		//文字コード
	private $to;		//To
	private $from;		//From
	private $title;		//タイトル
	private $contents;	//置換する配列
	
	//コンストラクタ
	//引数：テンプレートファイル、文字コード
	function sendmail($tmp_path,$lang="ja"){
		$this->lang = $lang;
		$this->template = file_get_contents($tmp_path);
		//文字コード変換
		$this->template = $this->conv($this->template);
	}
	
	//メアド妥当性チェック
	//http://fdays.blogspot.com/2007/10/rfc-2822-j0hn-d0e-10-pregmatch-9.htmlより拝借
	private function chk_email($value){
		return preg_match('/^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i', $value);
	}
	//文字コード変換
	private function conv($in_data){
		return mb_convert_encoding($in_data,"EUC-JP", "EUC-JP,UTF-8,SJIS,JIS,ASCII");//autoだと文字化けするため
	}

	//プロパティたち
	//Toを指定
	function set_to($to){
		If ($this->chk_email($to)){
			$this->to = $to;
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//Fromを指定
	function set_from($from){
		If ($this->chk_email($from)){
			$this->from = $from;
			return TRUE;
		}else{
		echo $this->chk_email($from);
			return FALSE;
		}
	}
	//Titleを指定
	function set_title($title){
		If ($title){
			$this->title = $title;
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	protected function replace_options($matches) {
		//引数にはpreg_replace_callbackよりマッチした文字列が引き渡される
		//$this->contents配列にマッチした文字列のキーが存在した場合、
		//マッチしたキーに対応する配列の中身を返す。
		//マッチしなければブランクを返す
		print_r( $matches);
	  	if (array_key_exists($matches[1], $this->contents)) {
	      return $this->contents[$matches[1]];
	    } else {
	      return "";
	    }
	  }

	//メール送信
	function send($in_data = array()){
		$this->contents = $in_data;
		//テンプレートに配列の値をセット
		//http://d.hatena.ne.jp/tilfin/20080714/1216108930/を参考にする
		//置換対象文字列は{$XXX}で、XXXの箇所は半角英数a-z,0-9まで
		$content = preg_replace_callback('/\{\$([a-z0-9]+)\}/',
                 array($this, "replace_options"), $this->template);
		
		//セット
		$this->title = str_replace(array("\r\n","\n","\r"), '', $this->title);//タイトルは改行が入るといけない
		$this->title = trim($this->title);//念のため
		$this->from = "From:" . $this->from;//from
		//本文の改行コードはLFのみ
		$content = str_replace(array("\r\n","\n","\r"), "\n", $content);
		//一行は７０文字まで
		$content = wordwrap($content, 70);
		
		//文字コード変換
		$this->to = $this->conv($this->to);
		$this->title = $this->conv($this->title);
		$content = $this->conv($content);
		$this->from = $this->conv($this->from);
		
		//送信
		mb_language($this->lang);
		return mb_send_mail($this->to,$this->title,$content,$this->from);
	
	}
}

?>
