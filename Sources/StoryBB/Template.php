<?php

/**
 * This class encapsulates all of the behaviours required by StoryBB's template system.
 *
 * @package StoryBB (storybb.org) - A roleplayer's forum software
 * @copyright 2017 StoryBB and individual contributors (see contributors.txt)
 * @license 3-clause BSD (see accompanying LICENSE file)
 *
 * @version 3.0 Alpha 1
 */

namespace StoryBB;

use LightnCandy\LightnCandy;
use StoryBB\Template\Cache;

class Template
{
	private static $helpers = [];
	private static $layout_loaded = '';
	private static $layout_template = '';

	private static $debug = [
		'template' => [],
		'partial' => [],
		'cache_hit' => [],
		'cache_miss' => [],
	];

	/**
	 * Set up the default helpers.
	 */
	private static function add_default_helpers()
	{
		static $done = false;
		if ($done) {
			return;
		} else {
			$done = true;
		}

		// Add the logic helpers.
		self::add_helper(Template\Helper\Logic::_list());

		// And the math helpers.
		self::add_helper(Template\Helper\Math::_list());

		// And some string helpers.
		self::add_helper(Template\Helper\Text::_list());

		// And some array helpers too.
		self::add_helper(Template\Helper\Arrays::_list());

		// And helpers for sessions and tokens to make life easier.
		self::add_helper(Template\Helper\Session::_list());

		// Generic controls need some care.
		self::add_helper(Template\Helper\Controls::_list());

		// And everything else.
		self::add_helper(Template\Helper\Misc::_list());
	}

	/**
	 * Add a template helper.
	 *
	 * @param array $helper_array Key/value, key is helper name, value is its callable
	 */
	public static function add_helper(array $helper_array) {
		self::$helpers += $helper_array;
	}

	/**
	 * Loads a template layout.
	 *
	 * @param string $partial Layout name, without root path or extension
	 */
	public static function set_layout($layout) {
		global $settings;

		if ($layout === 'raw') {
			self::$layout_loaded = 'raw';
			self::$layout_template = '{{{content}}}';
		}

		$paths = [
			'theme' => $settings['theme_dir'] . '/layouts',
			'default' => $settings['default_theme_dir'] . '/layouts',
		];

		foreach ($paths as $source => $path) {
			if (file_exists($path) && file_exists($path . '/' . $layout . '.hbs')) {
				self::$layout_loaded = $layout;
				self::$layout_template = file_get_contents($path . '/' . $layout . '.hbs');
				self::$debug['template'][] = $layout . ' (' . $source . ' layout)';
				break;
			}
		}

		if (empty(self::$layout_template)) {
			fatal_error('Could not load layout ' . $layout);
		}
	}

	/**
	 * Loads a template file.
	 *
	 * @param string $template Template name
	 * @return string Template contents
	 */
	public static function load($template) {
		global $settings;

		$paths = [
			'theme' => $settings['theme_dir'] . '/templates',
			'default' => $settings['default_theme_dir'] . '/templates',
		];

		foreach ($paths as $source => $path) {
			if (file_exists($path) && file_exists($path . '/' . $template . '.hbs')) {
				self::$debug['template'][] = $template . ' (' . $source . ')';
				return file_get_contents($path . '/' . $template . '.hbs');
			}
		}

		fatal_error('Could not load template ' . $template);
	}

	/**
	 * Loads a template partial.
	 *
	 * @param string $partial Partial name, without root path or extension
	 * @return string Partial template contents
	 */
	public static function load_partial($partial) {
		global $settings;

		$paths = [
			'theme' => $settings['theme_dir'] . '/partials',
			'default' => $settings['default_theme_dir'] . '/partials',
		];

		foreach ($paths as $source => $path) {
			if (file_exists($path) && file_exists($path . '/' . $partial . '.hbs')) {
				self::$debug['partial'][$partial] = $partial . ' (' . $source . ')';
				return file_get_contents($path . '/' . $partial . '.hbs');
			}
		}

		fatal_error('Could not load partial ' . $partial);
	}

	public static function compile(string $template, array $options = [], string $cache_id = '') {
		global $context, $cachedir, $modSettings;

		$phpStr = Cache::fetch($cache_id);
		if (!empty($phpStr))
		{
			self::$debug['cache_hit'][] = $cache_id;
			return $phpStr;
		}

		self::add_default_helpers();

		$default_partials = [
			'helpicon' => self::load_partial('helpicon'),
		];

		if (!empty($modSettings['debug_templates'])) {
			if (!isset($options['flags'])) {
				$options['flags'] = LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL;
			}
			$options['flags'] |= LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_RENDER_DEBUG;
		}

		$phpStr = LightnCandy::compile($template, [
			'flags' => isset($options['flags']) ? $options['flags'] : (LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL),
			'helpers' => !empty($options['helpers']) ? array_merge(self::$helpers, $options['helpers']) : self::$helpers,
			'partialresolver' => 'loadTemplatePartialResolver',
			'partials' => !empty($options['partials']) ? array_merge($default_partials, $options['partials']) : $default_partials,
		]);

		if (!empty($cache_id))
			self::$debug['cache_miss'][] = $cache_id;
		else
			self::$debug['cache_miss'][] = 'unknown';
		
		Cache::push($cache_id, $phpStr);

		return $phpStr;
	}

	public static function prepare($phpStr, array $data)
	{
		if (is_callable($phpStr))
		{
			return $phpStr($data);
		}

		$renderer = LightnCandy::prepare($phpStr);
		return $renderer($data);
	}

	public static function render(array $data)
	{
		if (empty(self::$layout_template))
		{
			self::set_layout('default');
		}

		$phpStr = self::compile(self::$layout_template, [
			'helpers' => [
				'locale' => 'locale_helper',
				'login_helper' => 'login_helper',
				'isSelected' => 'isSelected',
				'javascript' => 'template_javascript',
				'css' => 'template_css',
			]
		], 'layout-' . (!empty(self::$layout_loaded) ? self::$layout_loaded : 'default'));

		echo self::prepare($phpStr, $data);
	}

	public static function add(string $name, string $position = 'after', $relative = null)
	{
		global $context;
		if (!is_array($context['sub_template'])) {
			$context['sub_template'] = [$context['sub_template']];
		}

		if ($relative !== null) {
			$array_pos = array_search($relative, $context['sub_template']);
			if ($array_pos === false) {
				$relative = null;
			}
		}

		if ($position === 'after') {
			if ($relative === null) {
				$context['sub_template'][] = $name;
			} else {
				array_splice($context['sub_template'], $array_pos, 1, [$relative, $name]);
			}
		}

		if ($position === 'before') {
			if ($relative === null) {
				array_unshift($name, $context['sub_template']);
			} else {
				array_splice($context['sub_template'], $array_pos, 1, [$name, $relative]);
			}
		}
	}

	public static function get_debug_info()
	{
		return self::$debug;
	}
}

?>