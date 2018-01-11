<?php 
/**
 * @package plugins.drm
 * @subpackage Admin
 */
class Form_AdminApi extends Infra_Form
{
	public function init()
	{
		$this->setAttrib('id', 'frmAdminApi');
		$this->setDecorators(array(
			'FormElements', 
			array('HtmlTag', array('tag' => 'fieldset')),
			array('Form', array('class' => 'simple')),
		));

		$this->addElement('text', 'PartnerIdForApi', array(
			'label'			=> 'Partner ID:',
			'filters'		=> array('StringTrim'),
		));

		$this->addElement('select', 'drmType', array(
			'label'			=> 'DRM Type:',
			'filters'		=> array('StringTrim'),
			'multiOptions'	=> array('cenc' => 'cenc', 'fps' => 'fps'),
		));

		$this->addElement('select', 'adminApiAction', array(
			'label'			=> 'Action:',
			'filters'		=> array('StringTrim'),
			'multiOptions'	=> array('Get' => AdminApiActionType::GET, 'Add' => AdminApiActionType::ADD, 'Remove' => AdminApiActionType::REMOVE),
		));

		// submit button
		$this->addElement('button', 'submit', array(
			'ignore'	=> true,
			'label'		=> 'Execute',
			'onclick'		=> "adminApi($('#PartnerIdForApi').val(), $('#drmType').val(), $('#adminApiAction').val())",
			'decorators' => array('ViewHelper')
		));
	}
}