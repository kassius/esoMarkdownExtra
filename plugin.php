<?php

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["esoMarkdownExtra"] = array(
    "name"        => "esoMarkdownExtra",
    "description" => "This plugin uses the Markdown Extra library from Michel Fortin to render text.",
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

	public function links()
	{
		// Convert normal links - http://www.example.com, www.example.com - using a callback function.
		$this->content = preg_replace_callback(
			"/(?<=\s|^|>|&lt;)(\w+:\/\/)?([\w\-\.]+\.(?:AC|AD|AE|AERO|AF|AG|AI|AL|AM|AN|AO|AQ|AR|ARPA|AS|ASIA|AT|AU|AW|AX|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BIZ|BJ|BM|BN|BO|BR|BS|BT|BV|BW|BY|BZ|CA|CAT|CC|CD|CF|CG|CH|CI|CK|CL|CM|CN|CO|COM|COOP|CR|CU|CV|CW|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EDU|EE|EG|ER|ES|ET|EU|FI|FJ|FK|FM|FO|FR|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GOV|GP|GQ|GR|GS|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|IL|IM|IN|INFO|INT|IO|IQ|IR|IS|IT|JE|JM|JO|JOBS|JP|KE|KG|KH|KI|KM|KN|KP|KR|KW|KY|KZ|LA|LB|LC|LI|LK|LR|LS|LT|LU|LV|LY|MA|MC|MD|ME|MG|MH|MIL|MK|ML|MM|MN|MO|MOBI|MP|MQ|MR|MS|MT|MU|MUSEUM|MV|MW|MX|MY|MZ|NA|NAME|NC|NE|NET|NF|NG|NI|NL|NO|NP|NR|NU|NZ|OM|ORG|PA|PE|PF|PG|PH|PK|PL|PM|PN|POST|PR|PRO|PS|PT|PW|PY|QA|RE|RO|RS|RU|RW|SA|SB|SC|SD|SE|SG|SH|SI|SJ|SK|SL|SM|SN|SO|SR|ST|SU|SV|SX|SY|SZ|TC|TD|TEL|TF|TG|TH|TJ|TK|TL|TM|TN|TO|TP|TR|TRAVEL|TT|TV|TW|TZ|UA|UG|UK|US|UY|UZ|VA|VC|VE|VG|VI|VN|VU|WF|WS|XXX|YE|YT|ZA|ZM|ZW)(?:[\.\/#][^\s<]*?)?)(?=\)\s|[\s\.,?!>]*(?:\s|&gt;|>|$))/i",
			array($this, "linksCallback"), $this->content);

		// Convert email links.
		$this->content = preg_replace("/[\w-\.]+@([\w-]+\.)+[\w-]{2,4}/i", "<a href='mailto:$0' class='link-email'>$0</a>", $this->content);

		return $this;
	}

	public function quotes()
	{
		// Starting from the innermost quote, work our way to the outermost, replacing them one-by-one using a
		// callback function. This is the only simple way to do nested quotes without a lexer.
		$regexp = "/(.*?)\n?\[quote(?:=(.*?)(]?))?\]\n?(.*?)\n?\[\/quote\]\n{0,2}/ise";
		while (preg_match($regexp, $this->content)) {
			$this->content = preg_replace($regexp, "'$1'.\$this->makeQuote('$4', '$2$3')", $this->content);
		}

		return $this;
	}
	
	public function makeQuote($text, $citation = "")
	{
		// If there is a citation and it has a : in it, split it into a post ID and the rest.
		if ($citation and strpos($citation, ":") !== false)
			list($postId, $citation) = explode(":", $citation, 2);

		// Construct the quote.
		$quote = "<blockquote>\n";

		// If we extracted a post ID from the citation, add a "find this post" link.
		if (!empty($postId)) $quote .= "<a href='".URL(postURL($postId), true)."' rel='post' data-id='$postId' class='control-search postRef'><i class='icon-search'></i></a> ";

		// If there is a citation, add it.
		if (!empty($citation)) $quote .= "<cite>$citation</cite> ";

		// Finish constructing and return the quote.
		$quote .= "$text\n</blockquote>";
		return $quote;
	}

}

class ETPlugin_esoMarkdownExtra extends ETPlugin
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

		$search = array("\r&gt; ","\n&gt; ");
		$this->MDETFormat->content = str_replace($search, "\n> ", $this->MDETFormat->content);
		$this->MDETFormat->content = MarkdownExtra::defaultTransform($this->MDETFormat->content);

		$this->MDETFormat->content = str_replace("\r", "\n", $this->MDETFormat->content);
		while(strstr($this->MDETFormat->content,"\n\n") !== FALSE) { $this->MDETFormat->content = str_replace("\n\n", "", $this->MDETFormat->content); }

		$this->MDETFormat->format();
		$this->MDETFormat->content = str_replace("\\\"", "\"", $this->MDETFormat->content);

		$this->MDETFormat->closeTags();
		$sender->content = $this->MDETFormat->content;
	}

	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addCSSFile($this->resource("markdown.css"));
	}

}

?>
