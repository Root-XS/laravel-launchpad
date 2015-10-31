<?php

namespace Xs;

use Auth, Carbon, DateTime, DateTimeZone;

/**
 * Date & Time Helper class
 *
 * Provides helper functions for general use, specifically related to date & time.
 */
class DatetimeHelper {

	/**
	 * Constants.
	 */
	const TZ_EUROPE = [
		'Europe/London', // British
		'Europe/Amsterdam', // Central // or Rome, Paris, Stockholm, Berlin, Zurich?
		'Europe/Istanbul', // Eastern
		'Europe/Moscow', // Moscow
	];
	const TZ_NORTH_AMERICA = [
		'Greenland',
		'Canada/Newfoundland', // Newfoundland
		'Canada/Atlantic', // Atlantic
		'America/New_York', // Eastern
		'America/Chicago', // Central
		'America/Denver', // Mountain
		'America/Phoenix', // Mountain, no DST
		'America/Los_Angeles', // Pacific
		'America/Anchorage', // Alaska
		'America/Adak', // Hawaii
		'Pacific/Honolulu', // Hawaii, no DST
	];
	const TZ_SOUTH_AMERICA = [
		'America/Buenos_Aires', // Brazilian
		'America/Santiago', // Atlantic
		'America/Caracas',
		'America/Lima', // Eastern
	];

	/**
	 * Get list of weekdays.
	 *
	 * @param string $strFormat PHP date format
	 * @return array
	 */
	public static function getWeekdays($strFormat = 'D')
	{
		$aWeekdays = [];
		$i = 0;
		$oCarbon = Carbon::parse('next Sunday');
		while ($i < 7) {
			$aWeekdays[] = $oCarbon->addDay()->format($strFormat);
			$i++;
		}
		return $aWeekdays;
	}

	/**
	 * Convert a UTC time to the logged-in user's timezone.
	 *
	 * @param string $strDatetime The UTC datetime from the database.
	 * @param string $strFormat PHP date format to use for return val.
	 * @return string
	 */
	public static function utcToLocal($strDatetime, $strFormat = 'Y-m-d H:i:s')
	{
		$oDateTime = new DateTime($strDatetime);
		if (Auth::check())
			$oDateTime->setTimezone(new DateTimeZone(Auth::user()->timezone));
		return $oDateTime->format($strFormat);
	}

	/**
	 * Convert a localized time to UTC.
	 *
	 * @param string $strDatetime The local datetime from the user.
	 * @param string $strFormat PHP date format to use for return val.
	 * @return string
	 */
	public static function localToUtc($strDatetime, $strFormat = 'Y-m-d H:i:s')
	{
		if (Auth::check()) {
			$oDateTime = new DateTime($strDatetime, new DateTimeZone(Auth::user()->timezone));
			$oDateTime->setTimezone(new DateTimeZone('UTC'));
		} else {
			$oDateTime = new DateTime($strDatetime);
		}
		return $oDateTime->format($strFormat);
	}

	/**
	 * See if a User's timezone pushes a particular event to a different day.
	 *
	 * @param int|string $mEventTime The datetime or timestamp of the event.
	 * @return int -1 for back a day, +1 for forward a day, and 0 for no day change.
	 */
	public static function crossesDateline($mEventTime)
	{
		if (!is_numeric($mEventTime))
			$mEventTime = strtotime($mEventTime);

		$oDateTime = new DateTime(null, new DateTimeZone(Auth::user()->timezone));
		$iMorningDiff = strtotime(date('Y-m-d 00:00:00', $mEventTime)) - $mEventTime;
		$iNightDiff = strtotime(date('Y-m-d 00:00:00', $mEventTime + 60 * 60 * 24)) - $mEventTime;

		if ($oDateTime->getOffset() < 0 && $oDateTime->getOffset() < $iMorningDiff)
			return -1;
		elseif ($oDateTime->getOffset() > 0 && $oDateTime->getOffset() > $iNightDiff)
		 	return 1;
		else
			return 0;
	}

	/**
	 * Get a list of valid PHP timezones.
	 *
	 * @param bool $json Return as JSON string? (Default is PHP array)
	 * @return array|string
	 */
	public static function listTimezones($json = false)
	{
		// $aTimezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		$aTimezones = array_merge(
			self::TZ_NORTH_AMERICA,
			self::TZ_SOUTH_AMERICA,
			self::TZ_EUROPE
		);
		return $json ? json_encode($aTimezones) : $aTimezones;
	}

}
