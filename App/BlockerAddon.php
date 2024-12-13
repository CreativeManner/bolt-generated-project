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
        // Hook into Booknetic's calendar
        add_action('bkntc_calendar_footer', [$this, 'injectModalTemplate']);
        add_action('bkntc_calendar_toolbar_after', [$this, 'injectBlockerButton']);
        
        // Add assets
        add_action('bkntc_enqueue_assets', [$this, 'enqueueAssets']);

        // Ajax handlers
        add_action('wp_ajax_booknetic_save_blocker_settings', [$this, 'ajaxSaveBlockerSettings']);
        add_action('wp_ajax_booknetic_add_block', [$this, 'ajaxAddBlock']);
        add_action('wp_ajax_booknetic_get_blocks', [$this, 'ajaxGetBlocks']);
        add_action('wp_ajax_booknetic_delete_block', [$this, 'ajaxDeleteBlock']);

        // Filter calendar events to show blocks
        add_filter('bkntc_calendar_events', [$this, 'addBlockedEvents'], 10, 2);
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
        // Only load on calendar page
        if (!Helper::isCalendarPage()) {
            return;
        }

        wp_enqueue_style(
            'booknetic-blocker', 
            plugins_url('assets/css/blocker.css', dirname(__FILE__)), 
            ['booknetic'], 
            self::$version
        );

        wp_enqueue_script(
            'booknetic-blocker',
            plugins_url('assets/js/blocker.js', dirname(__FILE__)),
            ['jquery', 'booknetic'],
            self::$version,
            true
        );

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
        <div class="calendar-toolbar-container">
            <button type="button" class="btn btn-primary" id="booknetic-blocker-btn">
                <i class="fa fa-ban"></i> <?php echo __('Block Time', 'booknetic-blocker'); ?>
            </button>
        </div>
        <?php
    }

    public function injectModalTemplate()
    {
        require_once dirname(__DIR__) . '/view/blocker_interface.php';
    }

    public function addBlockedEvents($events, $parameters)
    {
        if (!Helper::getOption('booknetic_blocker_enabled', '1')) {
            return $events;
        }

        $blocks = Models\BlockedSlot::getByStaffAndDateRange(
            $parameters['staff_id'],
            $parameters['start'],
            $parameters['end']
        );

        foreach ($blocks as $block) {
            $events[] = [
                'id' => 'block_' . $block->id,
                'title' => __('Blocked', 'booknetic-blocker'),
                'start' => $block->date . ' ' . $block->start_time,
                'end' => $block->date . ' ' . $block->end_time,
                'className' => 'booknetic-blocked-slot',
                'color' => '#ff4d4d',
                'is_blocked' => true
            ];
        }

        return $events;
    }

    // ... rest of the methods remain the same ...
}
