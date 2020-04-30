<?php

namespace ic\Plugin\MissedSchedule;

use ic\Framework\Plugin\PluginClass;
use ic\Framework\Settings\Form\Section;
use ic\Framework\Settings\Settings;

/**
 * Class Backend
 *
 * @package ic\Plugin\MissedSchedule
 */
class Backend extends PluginClass
{

	/**
	 * @inheritdoc
	 */
	protected function initialize(): void
	{
		Settings::siteOptions($this->getOptions(), $this->name())
				->section('schedule', function (Section $section) {
					$section->title(__('Interval', $this->id()))
							->number('interval', __('Time in seconds', $this->id()));
				});
	}

}
