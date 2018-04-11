<?php
/**
 * @package plugins.reach
 * @subpackage Admin
 */
class Form_ReachProfileConfigure extends ConfigureForm
{
	protected $newPartnerId;
	protected $disableAttributes;
	const ADMIN_CONSOLE_RULE_PREFIX = "AutomaticAdminConsoleRule_";

	public function __construct($partnerId, $disableAttributes = null)
	{
		$this->newPartnerId = $partnerId;
		$this->disableAttributes = $disableAttributes;

		parent::__construct();
	}

	public function init()
	{
		$this->setAttrib('id', 'frmReachProfileConfigure');
		$this->setMethod('post');
		$titleElement = new Zend_Form_Element_Hidden('generalTitle');
		$titleElement->setLabel('General');
		$titleElement->setDecorators(array('ViewHelper', array('Label', array('placement' => 'append')), array('HtmlTag',  array('tag' => 'b'))));
		$this->addElement($titleElement);

		$this->addElement('text', 'id', array(
			'label'			=> 'ID:',
			'filters'		=> array('StringTrim'),
			'readonly'		=> true,
			'disabled'		=> 'disabled',
		));

		$this->addElement('text', 'partnerId', array(
			'label' 		=> 'Related Publisher ID:',
			'required'		=> true,
			'filters' 		=> array('StringTrim'),
			'placement' => 'prepend',
			'readonly'		=> 'true',
		));

		$profileType = new Kaltura_Form_Element_EnumSelect('profileType', array('enum' => 'Kaltura_Client_Reach_Enum_ReachProfileType'));
		$profileType->setLabel('Profile Type:');
		$profileType->setValue(Kaltura_Client_Reach_Enum_ReachProfileType::FREE_TRIAL);
		$this->addElement($profileType);

		$defaultSourceLanguageView = new Kaltura_Form_Element_EnumSelect('defaultSourceLanguage', array('enum' => 'Kaltura_Client_Reach_Enum_CatalogItemLanguage'));
		$defaultSourceLanguageView->setLabel('Default Source Language:');
		$defaultSourceLanguageView->setValue(Kaltura_Client_Reach_Enum_CatalogItemLanguage::EN);
		$this->addElement($defaultSourceLanguageView);

		$defaultOutputFormatView = new Kaltura_Form_Element_EnumSelect('defaultOutputFormat', array('enum' => 'Kaltura_Client_Reach_Enum_VendorCatalogItemOutputFormat'));
		$defaultOutputFormatView->setLabel('Default Output Format:');
		$defaultOutputFormatView->setValue(Kaltura_Client_Reach_Enum_VendorCatalogItemOutputFormat::SRT);
		$this->addElement($defaultOutputFormatView);

		$enableMachineModeration = new Kaltura_Form_Element_EnumSelect('enableMachineModeration', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$enableMachineModeration->setLabel('Enable Machine Moderation:');
		$enableMachineModeration->setRequired(true);
		$enableMachineModeration->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($enableMachineModeration);

		$enableHumanModeration = new Kaltura_Form_Element_EnumSelect('enableHumanModeration', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$enableHumanModeration->setLabel('Enable Human Moderation:');
		$enableHumanModeration->setRequired(true);
		$enableHumanModeration->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($enableHumanModeration);

		$autoDisplayMachineCaptionsOnPlayer = new Kaltura_Form_Element_EnumSelect('autoDisplayMachineCaptionsOnPlayer', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$autoDisplayMachineCaptionsOnPlayer ->setLabel('Auto Display Machine Captions On Player:');
		$autoDisplayMachineCaptionsOnPlayer ->setRequired(true);
		$autoDisplayMachineCaptionsOnPlayer ->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($autoDisplayMachineCaptionsOnPlayer );

		$autoDisplayHumanCaptionsOnPlayer = new Kaltura_Form_Element_EnumSelect('autoDisplayHumanCaptionsOnPlayer', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$autoDisplayHumanCaptionsOnPlayer ->setLabel('Auto Display Human Captions On Player:');
		$autoDisplayHumanCaptionsOnPlayer ->setRequired(true);
		$autoDisplayHumanCaptionsOnPlayer ->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($autoDisplayHumanCaptionsOnPlayer );

		$enableMetadataExtraction = new Kaltura_Form_Element_EnumSelect('enableMetadataExtraction', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$enableMetadataExtraction->setLabel('Enable Metadata Extraction:');
		$enableMetadataExtraction->setRequired(true);
		$enableMetadataExtraction->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($enableMetadataExtraction);

		$enableSpeakerChangeIndication = new Kaltura_Form_Element_EnumSelect('enableSpeakerChangeIndication', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$enableSpeakerChangeIndication->setLabel('Enable Speaker Change Indication:');
		$enableSpeakerChangeIndication->setRequired(true);
		$enableSpeakerChangeIndication->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($enableSpeakerChangeIndication);

		$enableAudioTags = new Kaltura_Form_Element_EnumSelect('enableAudioTags', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$enableAudioTags->setLabel('Enable Audion Tags:');
		$enableAudioTags->setRequired(true);
		$enableAudioTags->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($enableAudioTags);

		$enableProfanityRemoval = new Kaltura_Form_Element_EnumSelect('enableProfanityRemoval', array('enum' => 'Kaltura_Client_Enum_NullableBoolean', 	'excludes' => array (
			Kaltura_Client_Enum_NullableBoolean::NULL_VALUE)));
		$enableProfanityRemoval->setLabel('Enable Profanity Removal:');
		$enableProfanityRemoval->setRequired(true);
		$enableProfanityRemoval->setValue(Kaltura_Client_Enum_NullableBoolean::FALSE_VALUE);
		$this->addElement($enableProfanityRemoval);

		$this->addElement('text', 'maxCharactersPerCaptionLine', array(
			'label' 		=> 'Max Characters Per Caption Line:',
			'filters'		=> array('StringTrim'),
			'placement' => 'prepend',
		));

		$this->addRulesSection();
		$this->addRulesTemplate();

		$element3 = new Infra_Form_Html ( 'place_holder3', array ('content' => '<span/>' ) );
		$this->addElement ( $element3 );

		$this->addDisplayGroup(array('place_holder3'), 'reachProfileCredit', array(
			'legend' => 'Credit Configuration',
		));

		$this->addLine("Dictionaries Line");
		$this->addTitle('Reach Profile Dictionaries:');
		$this->addTitle('Max 1000 Characters per dictionary');

		$dictionariesSubForm = new Zend_Form_SubForm(array('DisableLoadDefaultDecorators' => true));
		$dictionariesSubForm ->addDecorator('ViewScript', array(
			'viewScript' => 'dictionaries-sub-form.phtml',
		));
		$this->addSubForm($dictionariesSubForm , 'ReachProfileDictionaries_');
		$innerDictionariesSubForm = new Form_DictionariesSubForm ('Kaltura_Client_Reach_Type_Dictionary');
		$this->addSubForm($innerDictionariesSubForm , "DictionaryTemplate");

	}

	public static $rulesMap = array("Kaltura_Client_Reach_Type_AddEntryVendorTaskAction" => "Automatic_Rule");

	private function addRulesSection()
	{
		$this->addLine("3");
		$this->addTitle('Reach Profile Rules:');

		$options = self::$rulesMap;
		$this->addSelectElement("ruleType", $options);

		$ruleSubForm = new Zend_Form_SubForm(array('DisableLoadDefaultDecorators' => true));
		$ruleSubForm->addDecorator('ViewScript', array(
			'viewScript' => 'rules-sub-form.phtml',
		));

		$this->addSubForm($ruleSubForm, 'ReachProfileRules_');

	}
	private function addRulesTemplate()
	{
		foreach(self::$rulesMap as $name => $class) {
			$ruleSubForm = new Form_RulesSubForm($name);
			$this->addSubForm($ruleSubForm, "reachProfileRuleTemplate_" . $class);
		}
	}

	public function populateFromObject($object, $add_underscore = true)
	{
		parent::populateFromObject($object, $add_underscore);

		$props = $object;
		if(is_object($object))
			$props = get_object_vars($object);

		$allElements = $this->getElements();
		foreach ($allElements as $element)
		{
			if ($element instanceof Kaltura_Form_Element_EnumSelect)
			{
				$elementName = $element->getName();
				if (isset($props[$elementName])) {
				    $element->setValue(array($props[$elementName]));
				}
			}
		}

		$this->populateDictionaries($object);
		$this->populateRules($object);
		$this->getSubForm("reachProfileCredit")->populateFromObject($object->credit);

	}

	private function populateDictionaries($object)
	{
		$dictionaries = array();
		foreach ($object->dictionaries as $dictionary)
		{
			$newDictionary = array();
			$newDictionary['language'] = $dictionary->language;
			$newDictionary['data'] = $dictionary->data;
			$dictionaries[] = $newDictionary;
		}
		if (!empty($dictionaries))
			$this->setDefault('ReachProfileDictionaries',  json_encode($dictionaries));
	}

	private function populateRules($object)
	{
		$rules = array();
		foreach ($object->rules as $rule)
		{
			if (substr($rule->description, 0, strlen(self::ADMIN_CONSOLE_RULE_PREFIX)) === self::ADMIN_CONSOLE_RULE_PREFIX)
			{
				$newRule = $this->createAutomaticRule($rule,self::$rulesMap['Kaltura_Client_Reach_Type_AddEntryVendorTaskAction'] );
				$newRule['description'] = $rule->description;
				$rules[] = $newRule;
			}
		}
		$this->setDefault('ReachProfileRules',  json_encode($rules));
	}

	private function createAutomaticRule($rule, $ruleType)
	{
		$newRule = array();
		$newRule['ruleType'] = $ruleType;
		$catalogItemIds = array();
		foreach ($rule->actions as $action)
		{
			/* @var Kaltura_Client_Reach_Type_AddEntryVendorTaskAction $action */
			$catalogItemIds[] = $action->catalogItemIds;
		}
		$newRule['catalogItemIds'] = implode(', ', $catalogItemIds);
		return $newRule;
	}

	public function getObject($objectType, array $properties, $add_underscore = true, $include_empty_fields = false)
	{
		$object = parent::getObject($objectType, $properties, $add_underscore,$include_empty_fields);

		$rules = $properties['ReachProfileRules'];
		$rulesArray = array();
		foreach (json_decode($rules) as $rule)
		{
			switch(array_search($rule->ruleType, self::$rulesMap))
			{
				case 'Kaltura_Client_Reach_Type_AddEntryVendorTaskAction':
				{
						$action = new Kaltura_Client_Reach_Type_AddEntryVendorTaskAction();
						$action->catalogItemIds = $rule->catalogItemIds;
						$description = ( empty($rule->description) || $rule->description == self::ADMIN_CONSOLE_RULE_PREFIX) ? (self::ADMIN_CONSOLE_RULE_PREFIX . mt_rand(100000, 999999)) : $rule->description ;
						$rulesArray[] = $this->getReachProfileRule(array($action), $description);
				}
			}
		}

		$object->rules = $rulesArray;
		$dictionaries = $properties['ReachProfileDictionaries'];
		$dictionariesArray = array();
		foreach ((array)json_decode($dictionaries) as $dictionary)
		{
			$dictionaryItem = new Kaltura_Client_Reach_Type_Dictionary();
			$dictionaryItem->language = $dictionary->language;
			$dictionaryItem->data = $dictionary->data;
			$dictionariesArray [] = $dictionaryItem;
		}
		$object->dictionaries = $dictionariesArray;

		$object->credit = $this->getSubForm("reachProfileCredit")->getObject($properties["reachProfileCredit"]);
		return $object;
	}

	public function getReachProfileRule($actions, $description = null)
	{
		$rule = new Kaltura_Client_Type_Rule();
		$rule->actions = $actions;
		if ($description)
			$rule->description = $description;
		return $rule;
	}

	/**
	 * Set to null all the attributes that shouldn't be updated
	 * @param Kaltura_Client_Reach_Type_ReachProfile $reachProfile
	 */
	public function resetUnUpdatebleAttributes(Kaltura_Client_Reach_Type_ReachProfile $reachProfile)
	{
		// reset readonly attributes
		$reachProfile->id = null;
		$reachProfile->partnerId = null;
		$reachProfile->createdAt = null;
		$reachProfile->updatedAt = null;
		$reachProfile->ReachProfileRules = null;
		$reachProfile->ReachProfileDictionaries = null;
	}
}
