<?php

/*
  Plugin Name: WP Bug Tracker
  Description: Tracks WordPress Errors and provide solutions.
  Version: 1.5
  Author: Vasyl Martyniuk  <martyniuk.vasyl@gmail.com>
  Author URI: http://phpsnapshot.com
 */

/*
  Copyright (C) <2012-2013>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * WP Bug Tracker main class
 *
 * @package WordPress\WP Bug Tracker
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/27/2012
 */
class AHM_HealthManager {

    /**
     * AHM Page ID
     */
    const AHM_PAGE = 'ahm';

    /**
     * Instance of itself
     *
     * @var AHM_HealthManager
     *
     * @access protected
     */
    protected static $_instance = NULL;

    /**
     * Contrcutor
     *
     * @access protected
     */
    protected function __construct() {
        if (is_admin()) { //make sure that this is activa only for Admin Panel
            add_action('admin_menu', array($this, 'admin_menu'), 999);
            add_action('admin_print_scripts', array($this, 'admin_scripts'));
            add_action('admin_print_styles', array($this, 'admin_styles'));
            add_action('wp_ajax_ahm', array($this, 'ajax'));
        }
    }

    /**
     * Manager AJAX Request
     *
     * @return mixed
     *
     * @access public
     */
    public function ajax() {
        echo Router::call('Ajax.process');
        exit;
    }

    /**
     * Register Backend Admin Menu
     *
     * @return void
     *
     * @access public
     */
    public function admin_menu() {
        $icon = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__));
        $icon .= '/media/active-menu.png';
        //get the number of errors
        $n = Router::call('Error.errorCount');
        $tail = ($n ? '<span class="ahm-notification">' . $n . '</span>' : '');
        //register the menu
        add_menu_page(
                __('Bug Tracker', 'ahm'),
                __('Bug Tracker' . $tail, 'ahm'),
                'administrator',
                self::AHM_PAGE,
                array($this, 'healthPage'), $icon
        );
    }

    /**
     * Print Scripts to header
     *
     * @return void
     *
     * @access public
     */
    public function admin_scripts() {
        Router::call('View.header', 'js');
    }

    /**
     * Print Styles to header
     *
     * @return void
     *
     * @access public
     */
    public function admin_styles() {
        Router::call('View.header', 'css');
    }

    /**
     * Render Health Manager Page
     *
     * @return void
     *
     * @access public
     */
    public function healthPage() {
        echo Router::call('View.retrieveView', 'index');
    }

    /**
     * Get Single instance of itself
     *
     * @return AHM_HealthManager
     *
     * @access public
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        //Error Log & Backup Structure initialization
        Router::call('Error.filebase');

        return self::$_instance;
    }

    /**
     * Activation Hook. Check for system requirements.
     *
     * @return void
     *
     * @access public
     * @global string $wp_version
     */
    public static function activate() {
        global $wp_version;
        //check WordPress Version
        if (version_compare($wp_version, '3.2', '<')) {
            exit(__('WordPress 3.2 or higher is required', 'ahm'));
        }
        //check PHP Version
        if (phpversion() < '5.1') {
            exit(__('PHP 5.1 or higher is required.', 'ahm'));
        }

        //Error Log & Backup Structure initialization
        Router::call('Error.filebase');
    }

    /**
     * Clean up the data
     *
     * @return void
     *
     * @access public
     * @static
     */
    public static function uninstall() {
        wp_clear_scheduled_hook('ahm_report_cron');
    }

    /**
     * Report Cron
     *
     * @return void
     *
     * @access public
     * @static
     */
    public static function cron() {
        $ui = Router::call('Settings.ui');

        //Error Log & Backup Structure initialization
        Router::call('Error.filebase');

        //analyze the logs first
        $analyzer = Router::call('Error.analyze');
        $analyzer->run(); //analyze 5000 lines

        //
        //check for available solutions
        $checkQueue = Router::call('Error.checkQueue');
        $checkQueue->run(); //report only 10 per hour
    }

}

if (!defined('AHM_LOADED')) { //check if error handler already was initiated
    require_once(realpath(dirname(__FILE__) . '/bootstrap.php'));
}
add_action('init', 'AHM_HealthManager::getInstance', 0);

//register activation & deactivation hooks
register_activation_hook(__FILE__, 'AHM_HealthManager::activate');
register_uninstall_hook(__FILE__, 'AHM_HealthManager::uninstall');