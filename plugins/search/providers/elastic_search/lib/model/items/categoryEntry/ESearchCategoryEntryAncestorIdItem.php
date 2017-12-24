<?php
/**
 * @package plugins.elasticSearch
 * @subpackage model.items
 */
class ESearchCategoryEntryAncestorIdItem extends ESearchBaseCategoryEntryItem implements IESearchCategoryEntryItem
{

	private static $allowed_search_types_for_field = array(
		ESearchCategoryEntryFieldName::ANCESTOR_ID => array('ESearchItemType::EXACT_MATCH'=> ESearchItemType::EXACT_MATCH),
	);

	public static function getAllowedSearchTypesForField()
	{
		return array_merge(self::$allowed_search_types_for_field, parent::getAllowedSearchTypesForField());
	}

	public function transformData()
	{
		$this->setFieldName(self::CATEGORY_IDS_MAPPING_FIELD);
		$categoryEntryStatus = $this->getCategoryEntryStatusSearchValue();
		$this->setSearchTerm(elasticSearchUtils::formatParentCategoryIdStatus($this->getSearchTerm(), $categoryEntryStatus));
	}

}
