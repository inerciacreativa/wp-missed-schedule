<?php

namespace ic\Plugin\MissedSchedule;

use ic\Framework\Plugin\Plugin;

/**
 * Class MissedSchedule
 *
 * @package ic\Plugin\MissedSchedule
 */
class MissedSchedule extends Plugin
{

	public const SCHEDULE = 'ic_missed_schedule';

	protected $logger;

	/**
	 * @inheritdoc
	 */
	protected function configure(): void
	{
		parent::configure();

		$this->setOptions([
			'interval' => MINUTE_IN_SECONDS * 5
		]);

		$this->hook()
			 ->on('cron_schedules', 'addSchedule')
			 ->on('wp_loaded', 'addEvent')
			 ->on(self::SCHEDULE, 'process');
	}

	/**
	 * @param array $schedules
	 *
	 * @return array
	 */
	protected function addSchedule(array $schedules): array
	{
		$schedules[self::SCHEDULE] = [
			'interval' => $this->getOption('interval'),
			'display'  => __('Missed Schedule Check', $this->id()),
		];

		return $schedules;
	}

	/**
	 *
	 */
	protected function addEvent(): void
	{
		if (!wp_next_scheduled(self::SCHEDULE)) {
			wp_schedule_event(time(), self::SCHEDULE, self::SCHEDULE);
		}
	}

	/**
	 *
	 */
	protected function process(): void
	{
		global $wpdb;

		$types = $this->getPostTypes();
		$sql   = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type in ($types) AND post_date > 0 AND post_date <= %s AND post_status = 'future'", current_time('mysql', 0));
		$ids   = $wpdb->get_col($sql);

		if (!empty($ids)) {
			foreach ($ids as $id) {
				wp_publish_post($id);
			}
		}
	}

	/**
	 * @return string
	 */
	protected function getPostTypes(): string
	{
		$types = get_post_types([
			'public'              => true,
			'exclude_from_search' => false,
			'_builtin'            => false,
		], 'names', 'and');

		$types[] = 'post';
		$types[] = 'page';

		return "'" . implode("', '", $types) . "'";
	}

}
