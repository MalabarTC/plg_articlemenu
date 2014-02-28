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

		echo "<pre>";
		echo "context:";
		var_dump($context);
		echo "article:";
		var_dump($article);
		echo "params:";
		var_dump($params);
		echo "page:";
		var_dump($page);
		die("</pre>");
	}
}
