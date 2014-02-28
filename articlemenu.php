<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.articlemenu
 *
 * @copyright   Copyright (C) EasyJoomla.org. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Plugin create anchor menu from an article generated form HTML headings.
 */
class PlgContentArticlemenu extends JPlugin
{
	/**
	 * Method to catch the onContentPrepare event.
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer  $page      The 'page' number
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Run this plugin for com_content.article only
		if ($context != 'com_content.article')
		{
			return true;
		}

		$articleContent = $article->introtext.$article->fulltext;

		echo "<pre>";
		echo "h3:";
		var_dump($this->getTags($articleContent, 'h3'));
		die("</pre>");
	}

	/**
	 * Method to get all appearances of the tag by tag name
	 *
	 * @param string $html / source code
	 * @param string $tag / html tag name
	 *
	 * @return  array
	 */
	public function getTags($html, $tag)
	{
		$tag = preg_quote($tag);

		$pattern = '/<'.$tag.'(| .*?)>(.*?)<\/'.$tag.'>/is';
		preg_match_all($pattern, $html, $matches, PREG_PATTERN_ORDER);
		return $matches[2];
	}
}
