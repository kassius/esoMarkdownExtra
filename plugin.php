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

class ETPlugin_MarkdownExtra extends ETPlugin
{
	public $md;

	public function handler_format_beforeFormat($sender)
	{
		$search = array("\r&gt; ","\n&gt; ");
		$sender->content = str_replace($search, "\n> ", $sender->content);;
		$sender->content = MarkdownExtra::defaultTransform($sender->content);
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
