<?php

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["MarkdownExtra"] = array(
    "name"        => "Markdown Extra",
    "description" => "This plugi uses the Markdown Extra Library from Michel Fortin to render text.",
    "version"     => "1.0",
    "author"      => "Kassius Iakxos",
    "authorEmail" => "kassius@users.noreply.github.com",
    "authorURL"   => "http://github.com/kassius",
    "license"     => "GPLv2"
);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

spl_autoload_register(function($class){
		require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});
use \Michelf\MarkdownExtra;

require_once(PATH_CORE."/lib/ETFormat.class.php");

class MDETFormat extends ETFormat
{
	public function format()
	{
		if (C("esoTalk.format.mentions")) $this->mentions();
		if (!$this->inline) $this->quotes();

		return $this;
	}
}

class ETPlugin_MarkdownExtra extends ETPlugin
{
	public $content;
	public $MDETFormat;

	public function init()
	{
		$this->MDETFormat = new MDETFormat;
	}

	public function handler_format_beforeFormat($sender)
	{
		$this->MDETFormat->content = $sender->get();
	}

	public function handler_format_afterFormat($sender)
	{
		$this->MDETFormat->links(); 

		$this->MDETFormat->inline(true);
		$search = array("\r&gt; ","\n&gt; ");
		$this->MDETFormat->content = str_replace($search, "\n> ", $this->MDETFormat->content);
		$this->MDETFormat->content = MarkdownExtra::defaultTransform($this->MDETFormat->content);

		$this->MDETFormat->content = str_replace("\r", "\n", $this->MDETFormat->content);
		while(strstr($this->MDETFormat->content,"\n\n") !== FALSE) { $this->MDETFormat->content = str_replace("\n\n", "", $this->MDETFormat->content); }

		$this->MDETFormat->inline(false);

		$this->MDETFormat->format();
		
		$sender->content = $this->MDETFormat->content;
	}

	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addCSSFile($this->resource("markdown.css"));
	}

	public function handler_memberController_renderBefore($sender)
	{
		$sender->addCSSFile($this->resource("markdown.css"));
	}

}

?>
