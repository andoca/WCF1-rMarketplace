<?php
// wcf imports
require_once (WCF_DIR . 'lib/form/AbstractForm.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * local search result of the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.map
 */
class RMarketplaceLocalSearchForm extends AbstractForm {
	public $templateName = 'rmarketplaceLocalSearch';
	public $RMarketplaceList = null;

	private $address = null;
	private $radius = null;

	private $distances = array();

	public $entries = array();

	/**
	 * current page
	 *
	 * @var integer
	 */
	public $pageNum = 1;

	/**
	 * total amount of entries
	 *
	 * @var integer
	 */
	public $totalEntries = 0;

	/**
	 * @see Page::readParameters();
	 */
	public function readFormParameters() {
		parent::readParameters();

		if (isset($_POST['address'])) $this->address = StringUtil::trim($_POST['address']);
		if (isset($_POST['radius'])) $this->radius = intval($_POST['radius']);

	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		$this->validateAddress();
		$this->validateRadius();
	}

	/**
	 * Validates the address
	 */
	protected function validateAddress() {

		// check word count
		if (strlen($this->address) < 4) {
			throw new UserInputException('address', 'tooShort');
		}
	}

	/**
	 * Validates the address
	 */
	protected function validateRadius() {
		if (! is_numeric($this->radius)) {
			throw new UserInputException('radius');
		}
	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		parent::readData();

		WCF::getUser()->checkPermission('user.rmarketplace.canList');
		if (! isset($this->address) || ! isset($this->radius)) throw new IllegalLinkException();
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
		parent::submit();

		$this->coord = GmapUtil::reverseGeocode(array(
				$this->address
		));

		$earthRadius = 6367449; // radius of earth in m --> spherical modell
		$earthRadiusKM = $earthRadius / 1000;
		$latDeg = 110900; // 1 lat deg in m


		$lngRad = $this->coord['lng'] * pi() / 180;
		$latRad = $this->coord['lat'] * pi() / 180;

		/* this old methode does not uses circles - it uses a square to get the items

		$lngDeg = cos($latRad) * pi() * $earthRadius / 180;

		$latM = 1 / $latDeg;
		$lngM = 1 / $lngDeg;

		$radius = $this->radius * 1000;

		$maxLat = $this->coord['lat'] + $radius * $latM;
		$minLat = $this->coord['lat'] - $radius * $latM;

		$maxLng = $this->coord['lng'] + $radius * $lngM;
		$minLng = $this->coord['lng'] - $radius * $lngM;

		$sql = "SELECT entryID, zipcode, lat, lng
				    FROM wcf" . WCF_N . "_rmarketplace_entries
				    WHERE
						lat >= '" . $minLat . "'
					AND lat <= '" . $maxLat . "'
					AND lng >= '" . $minLng . "'
					AND lng <= '" . $maxLng . "'";
	*/
		$sql = "SELECT entryID, (
				 " . $earthRadiusKM . " * SQRT(2*(1-cos(RADIANS(lat)) *
				 cos(" . $latRad . ") * (sin(RADIANS(lng)) *
				 sin(" . $lngRad . ") + cos(RADIANS(lng)) *
				 cos(" . $lngRad . ")) - sin(RADIANS(lat)) * sin(" . $latRad . ")))) AS distance

				 FROM wcf" . WCF_N . "_rmarketplace_entries WHERE

				 " . $earthRadiusKM . " * SQRT(2*(1-cos(RADIANS(lat)) *
				 cos(" . $latRad . ") * (sin(RADIANS(lng)) *
				 sin(" . $lngRad . ") + cos(RADIANS(lng)) *
				 cos(" . $lngRad . ")) - sin(RADIANS(lat)) * sin(" . $latRad . "))) <= " . $this->radius . "
				";
		if (! WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			$sql .= " AND (isDisabled = 0 OR userID = '" . WCF::getUser()->userID . "')";
		}
		$sql .= " ORDER BY time DESC";

		$query = WCF::getDB()->sendQuery($sql);
		while ( $row = WCF::getDB()->fetchArray($query) ) {
			$this->distances[$row['entryID']] = $row['distance'];
			$this->entries[] = new MpEntry($row['entryID']);
		}
	}

	public function distance($dblLat1, $dblLong1, $dblLat2, $dblLong2) {
		$earthRadius = 6367.449;

		//convert degrees to radians
		$dblLat1 = $dblLat1 * pi() / 180;
		$dblLong1 = $dblLong1 * pi() / 180;
		$dblLat2 = $dblLat2 * pi() / 180;
		$dblLong2 = $dblLong2 * pi() / 180;

		if ($dblLat1 != $dblLat2 || $dblLong1 != $dblLong2) {
			//the two points are not the same
			$dist = sin($dblLat1) * sin($dblLat2) + cos($dblLat1) * cos($dblLat2) * cos($dblLong2 - $dblLong1);
			$dist = $earthRadius * (- 1 * atan($dist / sqrt(1 - $dist * $dist)) + M_PI / 2);
		}
		return $dist;

	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.de.0xdefec.rmarketplace.header.menu');
		WCF::getTPL()->assign(array(

				'entries' => $this->entries,
				'coord' => $this->coord,
				'distances' => $this->distances,
				'address' => $this->address,
				'radius' => $this->radius
		));
	}
}
?>