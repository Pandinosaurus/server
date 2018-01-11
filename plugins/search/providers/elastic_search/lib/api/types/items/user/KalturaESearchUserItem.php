<?php
/**
 * @package plugins.elasticSearch
 * @subpackage api.objects
 */
class KalturaESearchUserItem extends KalturaESearchAbstractUserItem
{

	/**
	 * @var KalturaESearchUserFieldName
	 */
	public $fieldName;

	private static $map_between_objects = array(
		'fieldName'
	);

	private static $map_dynamic_enum = array();

	private static $map_field_enum = array(
		KalturaESearchUserFieldName::SCREEN_NAME => ESearchUserFieldName::SCREEN_NAME,
		KalturaESearchUserFieldName::EMAIL => ESearchUserFieldName::EMAIL,
		KalturaESearchUserFieldName::TYPE => ESearchUserFieldName::TYPE,
		KalturaESearchUserFieldName::TAGS => ESearchUserFieldName::TAGS,
		KalturaESearchUserFieldName::UPDATED_AT => ESearchUserFieldName::UPDATED_AT,
		KalturaESearchUserFieldName::CREATED_AT => ESearchUserFieldName::CREATED_AT,
		KalturaESearchUserFieldName::LAST_NAME => ESearchUserFieldName::LAST_NAME,
		KalturaESearchUserFieldName::FIRST_NAME => ESearchUserFieldName::FIRST_NAME,
		KalturaESearchUserFieldName::PERMISSION_NAMES => ESearchUserFieldName::PERMISSION_NAMES,
		KalturaESearchUserFieldName::GROUP_IDS => ESearchUserFieldName::GROUP_IDS,
		KalturaESearchUserFieldName::ROLE_IDS => ESearchUserFieldName::ROLE_IDS,
	);

	protected function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		if (!$object_to_fill)
			$object_to_fill = new ESearchUserItem();
		return parent::toObject($object_to_fill, $props_to_skip);
	}

	protected function getItemFieldName()
	{
		return $this->fieldName;
	}

	protected function getDynamicEnumMap()
	{
		return self::$map_dynamic_enum;
	}

	protected function getFieldEnumMap()
	{
		return self::$map_field_enum;
	}

}
