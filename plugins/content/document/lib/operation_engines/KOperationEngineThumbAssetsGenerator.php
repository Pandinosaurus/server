<?php
/**
 * @package plugins.document
 * @subpackage lib
 */
class KOperationEngineThumbAssetsGenerator extends KOperationEngineDocument
{
	const IMAGES_LIST_XML_NAME = 'imagesList.xml';
	private $realInFilePath;

	public function operate(kOperator $operator = null, $inFilePath, $configFilePath = null)
	{
		$this->realInFilePath = realpath($inFilePath);
		$this->generateThumbAssets($this->parseImagesListXML());

		if ( $this->data ) { //no output files to copy
			$this->data->destFileSyncLocalPath = null;
			$this->data->logFileSyncLocalPath = null;
		}

		return true;
	}

	private function parseImagesListXML(){
		$imagesList = array();
		$imagesXml = new SimpleXMLElement(file_get_contents($this->realInFilePath . DIRECTORY_SEPARATOR . self::IMAGES_LIST_XML_NAME));
		foreach ($imagesXml->item as $item) {
			$imagesList[] = (string)$item->name;
		}

		return $imagesList;
	}

	private function generateThumbAssets($imagesList)
	{
		if ( !$imagesList || count($imagesList)==0 )
		{
			KalturaLog::info('no slides, cannot generate thumb cue points');
			return;
		}

		KBatchBase::impersonate($this->job->partnerId);
		$entry = KBatchBase::$kClient->baseEntry->get($this->job->entryId);
		KBatchBase::unimpersonate();
		if ( !$entry || !$entry->parentEntryId ) {
			KalturaLog::info('no parentEntryId, cannot generate thumb cue points');
			return;
		}

		KBatchBase::impersonate($this->job->partnerId);
		KBatchBase::$kClient->startMultiRequest();
		$index = 0;
		foreach ($imagesList as $image) {
			$thumbCuePoint = new KalturaThumbCuePoint();
			$thumbCuePoint->entryId = $entry->parentEntryId;
			KBatchBase::$kClient->cuePoint->add( $thumbCuePoint ) ;
			$index++;

			$thumbAsset = new KalturaTimedThumbAsset();
			$thumbAsset->tags = $this->job->entryId;
			$thumbAsset->cuePointId = "{" . $index . ":result:id}";
			KBatchBase::$kClient->thumbAsset->add( $entry->parentEntryId, $thumbAsset) ;
			$index++;

			$resource = new KalturaServerFileResource();
			$resource->localFilePath = $this->realInFilePath . DIRECTORY_SEPARATOR . $image;
			KBatchBase::$kClient->thumbAsset->setContent("{" . $index . ":result:id}", $resource);
			$index++;
		}
		KBatchBase::$kClient->doMultiRequest();
		KBatchBase::unimpersonate();
	}
}