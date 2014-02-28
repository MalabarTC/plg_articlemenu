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

		$tag = $this->params->get('tag', 'h2');
		$headings = $this->getTags($article->text, $tag);

		if(count($headings) <= $this->params->get('minimum_items', 3))
		{
			return true;
		}

		$anchors = $this->prepareAnchors($headings);
		$article->text = $this->insertAnchors($article->text, $headings, $tag);
		$articleMenu = $this->generateArticleMenu($anchors);
		$article->text = $articleMenu.$article->text;
		$this->initJavascript();
		$this->initCss();
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

	/**
	 * Method to prepare an anchor array
	 *
	 * @param array $headings
	 *
	 * @return  array
	 */
	public function prepareAnchors($headings)
	{
		$anchors = array();

		if (isset($headings[0]))
		{
			foreach ($headings as $heading)
			{
				if ($heading)
				{
					$cleanHeading = trim(strip_tags($heading));
					$anchorId = JFilterOutput::stringURLSafe($cleanHeading);
					$anchors[$anchorId] = $cleanHeading;
				}
			}
		}

		return $anchors;
	}


	/**
	 * Insert anchors into the HTML code
	 *
	 * @param string $html / source code
	 * @param array $headings
	 * @param string $tag / html tag name
	 *
	 * @return  array
	 */
	public function insertAnchors($html, $headings, $tag)
	{
		if (isset($headings[0]))
		{
			foreach ($headings as $heading)
			{
				if ($heading)
				{
					$anchorId = JFilterOutput::stringURLSafe(trim(strip_tags($heading)));
					$html = preg_replace('#<'.$tag.'(|[^>]+)>\Q'.$heading.'\E</'.$tag.'>#is',
						'<div id="'.$anchorId.'" class="articlemenu"></div>$0',
						$html);
				}
			}
		}

		return $html;
	}

	/**
	 * Generate HTML for article menu
	 *
	 * @param array $anchors
	 *
	 * @return  string
	 */
	public function generateArticleMenu($anchors)
	{
		$menu = array();

		if(is_array($anchors))
		{
			$menu[] = '<div class="articlemenu-wrapper">';
			$menu[] = '<ul class="nav nav-list">';

			foreach($anchors as $anchorId => $anchorName)
			{
				$menu[] = '<li><a href="#'.$anchorId.'">'.$anchorName.'</a></li>';
			}
		
			$menu[] = '</ul>';
			$menu[] = '</div>';
		}
		
		return implode("\n", $menu);
	}

	/**
	 * Initialize Javascript
	 *
	 * @return  void
	 */
	public function initJavascript()
	{
		$js = "

			jQuery(function($) {
				$('body').scrollspy({ target: '.articlemenu-wrapper' });

				$('.articlemenu-wrapper ul li a[href^=\'#\']').on('click', function(e) {
					e.preventDefault();
					var hash = this.hash;
					$('html, body').animate({scrollTop: $(this.hash).offset().top - 30}, 300, function(){
						window.location.hash = hash;
					});

				});
			});

		";

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
	}

	/**
	 * Initialize CSS
	 *
	 * @return  void
	 */
	public function initCss()
	{
		$css = "

			.articlemenu-wrapper ul{
				position:fixed;
				background:#fff;
				width:200px;
				right:0;
				top:0;
				-webkit-border-bottom-left-radius: 10px;
				-moz-border-radius-bottomleft: 10px;
				border-bottom-left-radius: 10px;
				padding-top: 15px;
				padding-bottom: 15px;
				filter:alpha(opacity=50);
				opacity:0.5;
				-webkit-transition: all 0.2s ease-in-out;
				-moz-transition: all 0.2s ease-in-out;
				-ms-transition: all 0.2s ease-in-out;
				-o-transition: all 0.2s ease-in-out;
				transition: all 0.2s ease-in-out;
			}

			.articlemenu-wrapper ul:hover{
				filter:alpha(opacity=1);
				opacity:1;
			}

		";

		$document = JFactory::getDocument();
		$document->addStyleDeclaration($css);
	}
	
}
