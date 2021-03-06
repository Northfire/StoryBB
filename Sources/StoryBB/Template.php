<?php

/**
 * This class encapsulates all of the behaviours required by StoryBB's template system.
 *
 * @package StoryBB (storybb.org) - A roleplayer's forum software
 * @copyright 2018 StoryBB and individual contributors (see contributors.txt)
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
	private static $layout_source = '';
	private static $layers = [];

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

		// Generic XML needs some love too.
		self::add_helper(Template\Helper\Xml::_list());

		// And everything else.
		self::add_helper(Template\Helper\Misc::_list());
	}

	/**
	 * Add a template helper.
	 *
	 * @param array $helper_array Key/value, key is helper name, value is its callable
	 */
	public static function add_helper(array $helper_array)
	{
		self::$helpers += $helper_array;
	}

	/**
	 * Loads a template layout.
	 *
	 * @param string $partial Layout name, without root path or extension
	 */
	public static function set_layout($layout)
	{
		global $settings;

		if ($layout === 'raw') {
			self::$layout_loaded = 'raw';
			self::$layout_template = '{{{content}}}';
			self::$layout_source = '';
			return;
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
				self::$layout_source = 'theme' . $settings['theme_id'];
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
	public static function load($template)
	{
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
	 * @param bool $fatal_on_fail Whether to fail with a fatal error if the partial could not be loaded, or return empty.
	 * @return string Partial template contents
	 */
	public static function load_partial($partial, $fatal_on_fail = true): string
	{
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

		if ($fatal_on_fail)
			fatal_error('Could not load partial ' . $partial);

		return '';
	}

	public static function compile(string $template, array $options = [], string $cache_id = '')
	{
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
			'partialresolver' => function($cx, $name) {
				return \StoryBB\Template::load_partial($name);
			},
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

		$cache_id = '';

		if (empty(self::$layout_loaded) || self::$layout_loaded != 'raw')
		{
			$cache_id = 'layout-' . (!empty(self::$layout_loaded) ? self::$layout_loaded : 'default');
			if (!empty(self::$layout_source))
				$cache_id .= '-' . self::$layout_source;
		}

		$phpStr = self::compile(self::$layout_template, [
			'helpers' => [
				'locale' => 'locale_helper',
				'login_helper' => 'login_helper',
				'javascript' => 'template_javascript',
				'css' => function()
				{
					return template_css();
				},
			]
		], $cache_id);

		echo self::prepare($phpStr, $data);
	}

	public static function render_page(string $content)
	{
		global $context, $settings, $scripturl, $txt, $modSettings, $maintenance, $user_info, $options;

		$context['session_flash'] = session_flash_retrieve();

		$template_above = '';
		$template_below = '';
		if (!empty(self::$layers))
		{
			foreach (self::$layers as $layer)
			{
				$template = self::load_partial($layer . '_above', false);
				if ($template)
				{
					$phpStr = self::compile($template, [], 'partial-' . $layer . '_above-' . self::get_theme_id('partials', $layer . '_above'));
					$template_above .= new \LightnCandy\SafeString(self::prepare($phpStr, [
						'context' => &$context,
						'modSettings' => $modSettings,
						'settings' => $settings,
						'txt' => $txt,
						'scripturl' => $scripturl,
						'options' => $options,
						'user_info' => $user_info,
					]));
				}

				$template = self::load_partial($layer . '_below', false);
				if ($template)
				{
					$phpStr = self::compile($template, [], 'partial-' . $layer . 'below-' . self::get_theme_id('partials', $layer . 'below'));
					$template_below .= new \LightnCandy\SafeString(self::prepare($phpStr, [
						'context' => &$context,
						'modSettings' => $modSettings,
						'settings' => $settings,
						'txt' => $txt,
						'scripturl' => $scripturl,
						'options' => $options,
						'user_info' => $user_info,
					]));
				}
			}
		}

		self::render([
			'content' => $template_above . $content . $template_below,
			'context' => $context,
			'txt' => $txt,
			'scripturl' => $scripturl,
			'settings' => $settings,
			'maintenance' => $maintenance,
			'modSettings' => $modSettings,
			'options' => $options,
			'user_info' => $user_info,
			'copyright' => theme_copyright(),
		]);
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

	public static function get_theme_id(string $template_type, string $template_name): int
	{
		global $settings;

		if (!in_array($template_type, ['partials', 'templates', 'layouts']))
			fatal_error('Invalid template type: ' . $template_type);

		if ($settings['default_theme_dir'] == $settings['theme_dir'])
			return 1; // Default theme.

		if (!file_exists($settings['theme_dir'] . '/' . $template_type))
			return 1; // The entire folder doesn't exist in this theme, use default.

		if (file_exists($settings['theme_dir'] . '/' . $template_type . '/' . $template_name . '.hbs'))
			return (int) $settings['theme_id'];

		return 1; // Default theme ultimately used.
	}

	public static function add_layer(string $template_layer)
	{
		self::$layers[] = $template_layer;
	}

	public static function remove_layer(string $template_layer)
	{
		self::$layers = array_diff(self::$layers, [$template_layer]);
	}

	public static function remove_all_layers()
	{
		self::$layers = [];
	}

	public static function has_layers()
	{
		return !empty(self::$layers);
	}
}
