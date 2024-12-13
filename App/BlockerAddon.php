<?php

namespace BookneticAddon\Blocker;

use BookneticApp\Providers\Core\AddonLoader;
use BookneticApp\Providers\Helpers\Helper;
use BookneticApp\Providers\UI\SettingsMenuUI;

class BlockerAddon extends AddonLoader
{
    protected static $slug = 'blocker';
    protected static $version = '1.0.12';

    public function init()
    {
        // Add direct action hooks instead of relying on initHooks
        add_action('bkntc_enqueue_assets', [$this, 'enqueueAssets']);
        add_action('bkntc_calendar_page_footer', [$this, 'injectModalTemplate']);
        
        // Critical: Hook into the calendar toolbar
        add_action('bkntc_calendar_toolbar_after', [$this, 'injectBlockerButton']);
        
        // Ajax handlers
        add_action('wp_ajax_booknetic_save_blocker_settings', [$this, 'ajaxSaveBlockerSettings']);
        add_action('wp_ajax_booknetic_add_block', [$this, 'ajaxAddBlock']);
        add_action('wp_ajax_booknetic_get_blocks', [$this, 'ajaxGetBlocks']);
        add_action('wp_ajax_booknetic_delete_block', [$this, 'ajaxDeleteBlock']);
    }

    public function getAddonInfo()
    {
        return [
            'title'       => __('Time Blocker', 'booknetic-blocker'),
            'description' => __('Block time slots in the calendar', 'booknetic-blocker'),
            'icon'        => 'fa fa-ban',
            'version'     => self::$version,
            'min_booknetic_version' => '1.0',
            'category'    => 'general'
        ];
    }

    public function enqueueAssets()
    {
        // Only load assets on calendar page
        if (!Helper::isCalendarPage()) {
            return;
        }

        // Enqueue CSS
        wp_enqueue_style(
            'booknetic-blocker', 
            plugins_url('assets/css/blocker.css', dirname(__FILE__)), 
            ['booknetic'], 
            self::$version
        );

        // Enqueue JavaScript
        wp_enqueue_script(
            'booknetic-blocker',
            plugins_url('assets/js/blocker.js', dirname(__FILE__)),
            ['jquery', 'booknetic'],
            self::$version,
            true
        );

        // Localize script
        wp_localize_script('booknetic-blocker', 'BookneticBlockerData', [
            'enabled' => Helper::getOption('booknetic_blocker_enabled', '1'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('booknetic_blocker'),
            'texts' => [
                'blockTime' => __('Block Time', 'booknetic-blocker'),
                'cancel' => __('Cancel', 'booknetic-blocker'),
                'save' => __('Save', 'booknetic-blocker')
            ]
        ]);
    }

    public function injectBlockerButton()
    {
        if (Helper::getOption('booknetic_blocker_enabled', '1') != '1') {
            return;
        }

        ?>
        <button type="button" class="btn btn-primary" id="booknetic-blocker-btn">
            <i class="fa fa-ban"></i> <?php echo __('Block Time', 'booknetic-blocker'); ?>
        </button>
        <?php
    }

    // ... rest of the methods remain the same ...
}
