<?php
require_once (WCF_DIR . 'lib/acp/form/RMarketplaceCategoryAddForm.class.php');

/**
 * Form to edit the categories
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */

class RMarketplaceCategoryEditForm extends RMarketplaceCategoryAddForm {

	public $templateName = 'RMarketplaceCategoryEdit';

	public $activeMenuItem = 'wcf.acp.rmarketplace';

	public $neededPermissions = 'admin.rmarketplace.canAdministrate';

	/**
	 * action to perform
	 *
	 * @var string
	 */
	public $action;

	public $catID = 0;

	public $catName = '';

	public $catOrder = '';

	public $catParent = 0;

	public $catDescription = '';

	public $catIcon = '';

	public $catInfo = '';

	public $categories = array ();

	public $from;

	public $to;

	/**
	 * true if the entry should be deleted
	 *
	 * @var integer
	 */
	protected $delete;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET ['action']))
			$this->action = StringUtil::trim($_GET ['action']);
		if (isset($_GET ['catID']))
			$this->catID = intval($_GET ['catID']);
		if (isset($_GET ['from']))
			$this->from = intval($_GET ['from']);
		if (isset($_GET ['to']))
			$this->to = intval($_GET ['to']);
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST ['catName']))
			$this->catName = StringUtil::trim($_POST ['catName']);
		if (isset($_POST ['catOrder']))
			$this->catOrder = intval($_POST ['catOrder']);
		if (isset($_POST ['catParent']))
			$this->catParent = intval($_POST ['catParent']);
		if (isset($_POST ['catDescription']))
			$this->catDescription = StringUtil::trim($_POST ['catDescription']);
		if (isset($_POST ['catIcon']))
			$this->catIcon = StringUtil::trim($_POST ['catIcon']);
		if (isset($_POST ['catInfo']))
			$this->catInfo = StringUtil::trim($_POST ['catInfo']);
		if (isset($_POST ['catID']))
			$this->catID = intval($_POST ['catID']);
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		
		if (isset($this->delete)) {
			rmCategoryEditor::delete($this->catID);
			HeaderUtil::redirect('index.php?page=RMarketplaceCategoryList' . SID_ARG_2ND_NOT_ENCODED);
			exit();
		} else {
			rmCategoryEditor::update($this->catID, $this->catName, $this->catOrder, $this->catParent, $this->catDescription, $this->catIcon, $this->catInfo);
		}
		
		// set saved
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (isset($this->action) && $this->action == 'delete') {
			rmCategoryEditor::delete($this->catID);
			HeaderUtil::redirect('index.php?page=RMarketplaceCategoryList' . SID_ARG_2ND_NOT_ENCODED);
			exit();
		} else if (isset($this->action) && $this->action == 'move') {
			rmCategoryEditor::moveItems($this->from, $this->to);
			HeaderUtil::redirect('index.php?page=RMarketplaceCategoryList&moved=1' . SID_ARG_2ND_NOT_ENCODED);
			exit();
		}
		
		$this->category = new rmCategory($this->catID);
		
		$cats = new rmCategory(null);
		$this->categories = $cats->getCategoriesSelect();
		$children = $this->category->getChildrenIDs();
		
		foreach ($children as $child) {
			unset($this->categories [$child]);
		}
		
		unset($this->categories [$this->category->catID]);
	
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array (
				'form' => $this, 
				'category' => $this->category 
		));
	}
}
?>