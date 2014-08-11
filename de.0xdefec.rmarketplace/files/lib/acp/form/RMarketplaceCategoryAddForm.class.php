<?php
require_once (WCF_DIR . 'lib/acp/form/ACPForm.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategoryEditor.class.php');

/**
 * Form to edit the categories
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */

class RMarketplaceCategoryAddForm extends ACPForm {

	public $templateName = 'RMarketplaceCategoryAdd';

	public $activeMenuItem = 'wcf.acp.rmarketplace';

	public $neededPermissions = 'admin.rmarketplace.canAdministrate';

	public $catID = 0;

	public $catName = '';

	public $catOrder = '';

	public $catParent = 0;

	public $catDescription = '';

	public $catIcon = '';

	public $catInfo = '';

	public $categories = array ();

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST ['catID']))
			$this->catID = intval($_POST ['catID']);
		if (isset($_POST ['catName']))
			$this->catName = ArrayUtil::trim($_POST ['catName']);
		if (isset($_POST ['catOrder']))
			$this->catOrder = intval($_POST ['catOrder']);
		if (isset($_POST ['catParent']))
			$this->catParent = intval($_POST ['catParent']);
		if (isset($_POST ['catDescription']))
			$this->catDescription = ArrayUtil::trim($_POST ['catDescription']);
		if (isset($_POST ['catInfo']))
			$this->catInfo = ArrayUtil::trim($_POST ['catInfo']);
		if (isset($_POST ['catIcon']))
			$this->catIcon = ArrayUtil::trim($_POST ['catIcon']);
	}

	/**
	 * Validates the name
	 */
	protected function validateCatName() {
		if (empty($this->catName)) {
			throw new UserInputException('catName', 'empty');
		}
	}

	/**
	 * Validates the order
	 */
	protected function validateCatOrder() {
		if (!is_numeric($this->catOrder)) {
			throw new UserInputException('carOrder', '');
		}
	}

	/**
	 * Validates the parent cat
	 */
	protected function validateCatParent() {
		if (!is_numeric($this->catParent)) {
			throw new UserInputException('catParent', '');
		}
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		$this->validateCatName();
		$this->validateCatOrder();
		$this->validateCatParent();
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		/**
		 * Insert a new ad
		 */
		rmCategoryEditor::add($this->catName, $this->catOrder, $this->catParent, $this->catDescription, $this->catIcon, $this->catInfo);
		
		HeaderUtil::redirect('index.php?page=RMarketplaceCategoryList' . SID_ARG_2ND_NOT_ENCODED);
		exit();
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
		
		$cats = new rmCategory(null);
		$this->categories = $cats->getCategoriesSelect();
		
		// remove the cat from the categories list
		$tmp = array_flip($this->categories);
		unset($tmp [$this->catID]);
		$this->categories = array_flip($tmp);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array (
				'form' => $this, 
				'categories' => $this->categories 
		));
	}
}
?>