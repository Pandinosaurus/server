<?php


class kESearchQueryManager
{
	/**
	 * Uses visitor/visited design pattern in order to create a search query

	 * @param ESearchItem $eSearchItem
	 * @return array - represents a sub elastic query.
	 */
	public static function createSearchQuery(ESearchItem $eSearchItem)
	{
		return $eSearchItem->createSearchQuery();
	}

	public static function createOperatorSearchQuery(ESearchOperator $eSearchOperator)
	{
		if (!count($eSearchOperator->getSearchItems()))
		{
			return array();
		}
		$additionalParams = array();
		switch ($eSearchOperator->getOperator())
		{
			case ESearchOperatorType::AND_OP:
				$boolOperator = 'must';
				break;
			case ESearchOperatorType::OR_OP:
				$boolOperator = 'should';
				$additionalParams['minimum_should_match'] = 1;
				break;
			default:
				KalturaLog::crit('unknown operator type');
				return null;
		}
		$outQuery = array();
		$categorizedSearchItems = array();
		//categorize each different search item by type.
		foreach ($eSearchOperator->getSearchItems() as $searchItem)
		{
			/**
			 * @var ESearchItem $searchItem
			 */
			if (!isset($categorizedSearchItems[$searchItem->getType()."SearchItems"]))
				$categorizedSearchItems[$searchItem->getType()."SearchItems"] = array();
			$categorizedSearchItems[$searchItem->getType()."SearchItems"][] = $searchItem;
		}

		if (isset($categorizedSearchItems['captionSearchItems']))
			$outQuery['bool']['must'][] = self::createCaptionSearchQuery($categorizedSearchItems['captionSearchItems'], $boolOperator, $additionalParams);

		if (isset($categorizedSearchItems['entrySearchItems']))
		{
			//TODO: partial won't work on most of these since they are not indexed as ngram
			$entrySubQuery = self::createEntrySearchQuery($categorizedSearchItems['entrySearchItems'], $boolOperator);
			foreach($entrySubQuery as $queryVerb => $queryVal)
				$outQuery['bool'][$queryVerb][] = $queryVal;
		}


		if (isset($categorizedSearchItems['metadataSearchItems']))
			$outQuery['bool']['must'][] = self::createMetadataSearchQuery($categorizedSearchItems['metadataSearchItems'], $boolOperator, $additionalParams);

		if (isset($categorizedSearchItems['operatorSearchItems']))
		{
			foreach ($categorizedSearchItems['operatorSearchItems'] as $operatorSearchItem)
			{
				$outQuery['bool']['must'][] = self::createOperatorSearchQuery($operatorSearchItem);
			}
		}

		return $outQuery;
	}

	public static function createEntrySearchQuery(array $eEntrySearchItemsArr, $boolOperator, $additionalParams = array())
	{
		$queryOut = array();
		foreach ($eEntrySearchItemsArr as $entrySearchItem)
		{
			/**
			 * @var ESearchEntryItem $entrySearchItem
			 */
			$queryVerbs = $entrySearchItem->getQueryVerbs();
			$queryOut[$queryVerbs[0]][$queryVerbs[1]] = array($entrySearchItem->getFieldName() => strtolower($entrySearchItem->getSearchTerm()));
		}
		return $queryOut;
	}

	public static function createCaptionSearchQuery(array $eSearchCaptionItemsArr, $boolOperator, $additionalParams = null)
	{
		$captionQuery['nested']['path'] = 'caption_assets';
		$captionQuery['nested']['query']['nested']['inner_hits'] = array('size' => 10); //TODO: get this parameter from config
		$captionQuery['nested']['inner_hits'] = array('size' => 10, '_source' => false);
		$captionQuery['nested']['query']['nested']['path'] = "caption_assets.lines";
		foreach ($eSearchCaptionItemsArr as $eSearchCaptionItem)
		{
			/* @var ESearchCaptionItem $eSearchCaptionItem */
			switch ($eSearchCaptionItem->getItemType())
			{
				case ESearchItemType::EXACT_MATCH:
					$captionQuery['nested']['query']['nested']['query']['bool'][$boolOperator][] = array(
							'term' => array(
								'caption_assets.lines.content' => strtolower($eSearchCaptionItem->getSearchTerm())
							)
						);
					break;
				case ESearchItemType::PARTIAL:
					$captionQuery['nested']['query']['nested']['query']['bool'][$boolOperator][] = array(
						'multi_match' => array(
							'query' => strtolower($eSearchCaptionItem->getSearchTerm()),
							'fields' => array(
								'caption_assets.lines.content',
								'caption_assets.lines.content_*' //todo change here if we want to choose the language to search
							),
							'type' => 'most_fields'
						)
					);
					break;
				case ESearchItemType::STARTS_WITH:
					$captionQuery['nested']['query']['nested']['query']['bool'][$boolOperator][] = array(
						'prefix' => array(
							'caption_assets.lines.content' => strtolower($eSearchCaptionItem->getSearchTerm())
						)
					);
					break;
				case ESearchItemType::DOESNT_CONTAIN:
					$captionQuery['has_child']['query']['nested']['query']['bool']['must_not'][] = array(
						'term' => array(
							'caption_assets.lines.content' => strtolower($eSearchCaptionItem->getSearchTerm())
						)
					);
					break;
			}

			if (!is_null($eSearchCaptionItem->getStartTimeInVideo()))
			{
				$captionQuery['has_child']['query']['nested']['query']['bool'][$boolOperator][] = array('range' => array('lines.start_time' => array('gte' => $eSearchCaptionItem->getStartTimeInVideo())));
			}
			if (!is_null($eSearchCaptionItem->getEndTimeInVideo()))
			{
				$captionQuery['has_child']['query']['nested']['query']['bool'][$boolOperator][] = array('range' => array('lines.end_time' => array('gte' => $eSearchCaptionItem->getEndTimeInVideo())));
			}

		}
		foreach ($additionalParams as $addParamKey => $addParamVal)
		{
			$captionQuery['has_child']['query']['nested']['query']['bool'][$addParamKey] = $addParamVal;
		}
		return $captionQuery;
	}

	public static function createMetadataSearchQuery(array $eSearchMetadataItemsArr, $boolOperator, $additionalParams = null)
	{
		$metadataQuery['nested']['path'] = 'metadata';
		$metadataQuery['nested']['inner_hits'] = array('size' => 10, '_source' => true);
		foreach ($eSearchMetadataItemsArr as $metadataESearchItem)
		{
			/* @var ESearchMetadataItem $metadataESearchItem */
			switch ($metadataESearchItem->getItemType())
			{
				case ESearchItemType::EXACT_MATCH:
					$metadataQuery['nested']['query']['bool'][$boolOperator][] = array(
						'term' => array(
							'metadata.value_text' => strtolower($metadataESearchItem->getSearchTerm())
						)
					);
					break;
			}
			if ($metadataESearchItem->getXpath())
			{
				$metadataQuery['nested']['query']['bool'][$boolOperator][] = array(
					'term' => array(
						'metadata.xpath' => strtolower($metadataESearchItem->getXpath())
					)
				);
			}
			if ($metadataESearchItem->getMetadataProfileId())
			{
				$metadataQuery['nested']['query']['bool'][$boolOperator][] = array(
					'term' => array(
						'metadata.metadata_profile_id' => strtolower($metadataESearchItem->getMetadataProfileId())
					)
				);
			}
		}
		return $metadataQuery;
	}
}


?>



